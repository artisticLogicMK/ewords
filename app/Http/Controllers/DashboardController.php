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
            'cover' => 'nullable|image|max:2048', // validate image file if uploaded
            'slug' => 'required|string|max:255|unique:competitions,slug,' . ($competition->id ?? 'null'),
            'content' => 'nullable|string|max:100000',
            'past_winners_content' => 'nullable|string|max:100000',
            'winner' => 'nullable|string|max:255',
            'winner_pic' => 'nullable|image|max:2048',
            'first_runner' => 'nullable|string|max:255',
            'first_runner_pic' => 'nullable|image|max:2048',
            'second_runner' => 'nullable|string|max:255',
            'second_runner_pic' => 'nullable|image|max:2048',
            'registration_closes' => 'nullable|date',
            'first_voting_starts' => 'nullable|date',
            'first_voting_ends' => 'nullable|date',
            'second_voting_starts' => 'nullable|date',
            'second_voting_ends' => 'nullable|date',
        ]);


        $manager = new ImageManager(new Driver());

        $folder = "competitions/{$data['slug']}";

        foreach (['cover', 'winner_pic', 'first_runner_pic', 'second_runner_pic'] as $field) {
            if ($request->hasFile($field)) {
                $uploadedFile = $request->file($field);

                // Read image and encode as JPEG (75% quality)
                $image = $manager->read($uploadedFile->getRealPath())->toJpeg(75);

                $filename = "{$field}_" . Str::random(10) . '.jpg';
                $path = "{$folder}/{$filename}";

                Storage::disk('public')->put($path, (string) $image);

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
}
?>