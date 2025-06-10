<?php
namespace App\Http\Controllers;

use App\Models\Competition;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class DashboardController extends Controller
{
    public function index()
    {
        $competitions = Competition::orderBy('created_at', 'desc')->paginate(20);

        return Inertia::render('Dashboard', [
            'competitions' => $competitions,
        ]);
    }

    public function create()
    {
        return Inertia::render('dashboard/New');
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:competitions,slug',
        ]);

        $competition = Competition::create($validated);

        return redirect()->route('competition.show', $competition->slug);
    }

    
    public function show($slug)
    {
        $competition = Competition::where('slug', $slug)->firstOrFail();

        return Inertia::render('dashboard/Competition', [
            'competition' => $competition,
        ]);
    }


    public function update(Request $request, Competition $competition)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'active' => 'required',
            'voting_active' => 'required',
            'registration_active' => 'required',
            'cover' => $request->hasFile('cover') ? 'image|max:2048' : 'nullable', // validate image file if uploaded
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

        // 🔒 Prevent multiple active competitions
        if ($data['active'] == 1) {
            $otherActiveExists = Competition::where('active', 1)
                ->where('id', '!=', $competition->id)
                ->exists();

            if ($otherActiveExists) {
                return redirect()->back()
                    ->with('error', 'Only one competition can be ongoing at a time. Please stop all others before opening this one.');
            }
        }

        // 🔒 Prevent multiple active voting competitions
        if ($data['voting_active'] == 1) {
            $otherActiveExists = Competition::where('voting_active', 1)
                ->where('id', '!=', $competition->id)
                ->exists();

            if ($otherActiveExists) {
                return redirect()->back()
                    ->with('error', 'Only one competition can have voting active at a time. Please deactivate all others before activating this one.');
            }
        }

        // 🔒 Prevent multiple open registrations
        if ($data['registration_active'] == 1) {
            $otherActiveExists = Competition::where('registration_active', 1)
                ->where('id', '!=', $competition->id)
                ->exists();

            if ($otherActiveExists) {
                return redirect()->back()
                    ->with('error', 'Only one competition can be open for registration at a time. Please close the existing one before opening a new one.');
            }
        }


        foreach (['cover', 'winner_pic', 'first_runner_pic', 'second_runner_pic'] as $field) {
            if ($request->hasFile($field)) {

                $manager = new ImageManager(new Driver());

                $folder = "competitions/{$competition->slug}";
                
                if (!empty($competition->{$field}) && Storage::disk('public')->exists($competition->{$field})) {
                    Storage::disk('public')->delete($competition->{$field});
                }

                $uploadedFile = $request->file($field);

                // Read the image using Intervention Image
                $image = $manager->read($uploadedFile->getRealPath());

                // Check if width exceeds 1200px, and resize if needed
                if ($image->width() > 1200) {
                    // This automatically scales down if wider than 1200px and keeps aspect ratio
                    $image->scaleDown(width: 1200);
                }

                $jpegData = $image->toJpeg(75)->toString();

                $filename = "{$field}_" . Str::random(10) . '.jpg';
                $path = "{$folder}/{$filename}";

                Storage::disk('public')->put($path, (string) $jpegData);

                // Save image path to data array
                $data[$field] = $path;
            } else {
                unset($data[$field]); // Don't overwrite if no new image was uploaded
            }
        }

        $competition->update($data);

        return redirect()->route('competition.show', $competition->slug)
            ->with('success', 'Competition updated!');
    }


    public function destroy(Competition $competition)
    {
        // Delete all files associated with competition
        foreach (['cover', 'winner_pic', 'first_runner_pic', 'second_runner_pic'] as $field) {
            if ($competition->{$field} && Storage::disk('public')->exists($competition->{$field})) {
                Storage::disk('public')->delete($competition->{$field});
            }
        }

        // Delete competition from db
        $competition->delete();

        return redirect()->route('competitions')->with('success', 'Competition deleted.');
    }

}
?>