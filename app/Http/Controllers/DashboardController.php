<?php
namespace App\Http\Controllers;

use App\Models\Competition;
use App\Models\Contestant;
use App\Models\Terms;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    // 📄 Displays a paginated list of competitions on the dashboard
    public function index()
    {
        $competitions = Competition::orderBy('created_at', 'desc')->paginate(20);

        return Inertia::render('Dashboard', [
            'competitions' => $competitions,
        ]);
    }





    // 📄 Returns the view for creating a new competition
    public function create()
    {
        return Inertia::render('dashboard/New');
    }





    // 💾 Stores a new competition after validating input
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:competitions,slug',
        ]);

        $competition = Competition::create($validated);

        return redirect()->route('competition.show', $competition->slug);
    }





    // 📄 Shows details of a specific competition by slug
    public function show($slug)
    {
        $competition = Competition::where('slug', $slug)->firstOrFail();

        return Inertia::render('dashboard/Competition', [
            'competition' => $competition,
        ]);
    }




    // 📄 Returns the view for updating terms
    public function terms()
    {
        $terms = Terms::first();

        // If no terms row exists, create a default one
        if (!$terms) {
            $terms = Terms::create([
                'content' => '<p>Enter your terms and conditions here...</p>',
            ]);
        }

        return Inertia::render('dashboard/Terms', [
            'terms' => $terms,
        ]);
    }



    public function updateTerms(Request $request)
    {
        $data = $request->validate([
            'content' => 'required|string|max:100000',
        ]);

        $terms = Terms::first();

        if (!$terms) {
            return back()->with('error', 'Terms not found.');
        }

        $terms->update($data);

        return back()->with('success', 'Terms updated successfully.');
    }






    // 🔄 Updates competition details, including file uploads and validations
    public function update(Request $request, Competition $competition)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'active' => 'required',
            'voting_active' => 'required',
            'registration_active' => 'required',
            'cover' => $request->hasFile('cover') ? 'image|max:2048' : 'nullable',
            'description' => 'required|string|max:255',
            'content' => 'nullable|string|max:100000',
            'past_winners_content' => 'nullable|string|max:100000',
            'set_winners' => 'required',
            'winner' => 'nullable|string|max:255',
            'winner_pic' => $request->hasFile('winner_pic') ? 'image|max:2048' : 'nullable',
            'first_runner' => 'nullable|string|max:255',
            'first_runner_pic' => $request->hasFile('first_runner_pic') ? 'image|max:2048' : 'nullable',
            'second_runner' => 'nullable|string|max:255',
            'second_runner_pic' => $request->hasFile('second_runner_pic') ? 'image|max:2048' : 'nullable',
            'registration_closes' => 'nullable|date',
            'first_voting_starts' => 'nullable|date',
            'first_voting_ends' => 'nullable|date',
            'second_voting_starts' => 'nullable|date',
            'second_voting_ends' => 'nullable|date',
        ]);

        // 🛡️ Prevent multiple active competitions at the same time
        if ($data['active'] == 1) {
            $otherActiveExists = Competition::where('active', 1)
                ->where('id', '!=', $competition->id)
                ->exists();

            if ($otherActiveExists) {
                return redirect()->back()
                    ->with('error', 'Only one competition can be ongoing at a time. Please stop all others before opening this one.');
            }
        }

        // 🛡️ Prevent multiple competitions with voting active at once
        if ($data['voting_active'] == 1) {
            $otherActiveExists = Competition::where('voting_active', 1)
                ->where('id', '!=', $competition->id)
                ->exists();

            if ($otherActiveExists) {
                return redirect()->back()
                    ->with('error', 'Only one competition can have voting active at a time. Please deactivate all others before activating this one.');
            }
        }

        // 🛡️ Prevent multiple competitions open for registration at once
        if ($data['registration_active'] == 1) {
            $otherActiveExists = Competition::where('registration_active', 1)
                ->where('id', '!=', $competition->id)
                ->exists();

            if ($otherActiveExists) {
                return redirect()->back()
                    ->with('error', 'Only one competition can be open for registration at a time. Please close the existing one before opening a new one.');
            }
        }

        // 🖼️ Handle image uploads and resize them if necessary
        foreach (['cover', 'winner_pic', 'first_runner_pic', 'second_runner_pic'] as $field) {
            if ($request->hasFile($field)) {
                $manager = new ImageManager(new Driver());
                $folder = "competitions/{$competition->slug}";

                // Delete old file if it exists
                if (!empty($competition->{$field}) && Storage::disk('public')->exists($competition->{$field})) {
                    Storage::disk('public')->delete($competition->{$field});
                }

                $uploadedFile = $request->file($field);
                $image = $manager->read($uploadedFile->getRealPath());

                // Resize if width exceeds 1200px while keeping aspect ratio
                if ($image->width() > 1200) {
                    $image->scaleDown(width: 1200);
                }

                // Save the image as JPEG with compression
                $jpegData = $image->toJpeg(75)->toString();

                $filename = "{$field}_" . Str::random(10) . '.jpg';
                $path = "{$folder}/{$filename}";

                // Store image in public disk
                Storage::disk('public')->put($path, (string) $jpegData);
                $data[$field] = $path;
            } else {
                unset($data[$field]); // Skip updating field if no new image
            }
        }

        // ✅ Update competition with all validated and processed data
        $competition->update($data);

        return redirect()->route('competition.show', $competition->slug)
            ->with('success', 'Competition updated!');
    }





    // 🚥 Moves competition to second stage and filters contestants
    public function secondStage(Request $request, Competition $competition)
    {
        $data = $request->validate([
            'limit' => 'required|integer',
            'criteria' => 'required|integer',
        ]);

        DB::transaction(function () use ($data, $competition) {
            // 1️⃣ Select top contestants who meet the voting criteria
            $qualified = $competition->contestants()
                ->where('votes', '>=', $data['criteria'])
                ->orderByDesc('votes')
                ->orderBy('created_at')
                ->take($data['limit'])
                ->get();

            $qualifiedIds = $qualified->pluck('id');

            // 2️⃣ Delete contestants who didn't qualify, including their files
            $toDelete = $competition->contestants()->whereNotIn('id', $qualifiedIds)->get();
            foreach ($toDelete as $contestant) {
                if ($contestant->video_path) {
                    Storage::disk('public')->delete($contestant->video_path);
                }
                if ($contestant->picture_path) {
                    Storage::disk('public')->delete($contestant->picture_path);
                }
                $contestant->delete();
            }

            // 3️⃣ Reset votes for qualified contestants
            Contestant::whereIn('id', $qualifiedIds)->update(['votes' => 0]);

            // 4️⃣ Mark competition as second stage
            $competition->update(['stage' => '2nd']);
        });

        return back()->with('success', 'Second stage setup complete. Top contestants retained and votes reset.');
    }





    // ❌ Deletes a competition and associated files
    public function destroy(Competition $competition)
    {
        // Delete any uploaded media files associated with this competition
        foreach (['cover', 'winner_pic', 'first_runner_pic', 'second_runner_pic'] as $field) {
            if ($competition->{$field} && Storage::disk('public')->exists($competition->{$field})) {
                Storage::disk('public')->delete($competition->{$field});
            }
        }

        // Remove competition from the database
        $competition->delete();

        return redirect()->route('competitions')->with('success', 'Competition deleted.');
    }
}
?>
