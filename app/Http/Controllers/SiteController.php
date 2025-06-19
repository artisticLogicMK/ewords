<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use App\Models\Contestant;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

use Illuminate\Http\Request;

class SiteController extends Controller
{
    private array $hiddenFields = [
        'content',
        'past_winners_content',
        'winner',
        'winner_pic',
        'first_runner',
        'first_runner_pic',
        'second_runner',
        'second_runner_pic',
        'updated_at',
    ];


    public function home()
    {
        $competitions = Competition::latest()->limit(10)->get()->makeHidden($this->hiddenFields);

        return Inertia::render('Index', [
            'competitions' => $competitions,
            'ogMeta' => [
                'title' => 'Home - ' . config('app.fullname'),
                'image' => asset('/assets/social.png'),
            ],
        ]);
    }



    public function competitions()
    {
        $competitions = Competition::orderBy('created_at', 'desc')
            ->paginate(10)
            ->through(fn ($item) => $item->makeHidden($this->hiddenFields));

        return Inertia::render('Competitions', [
            'competitions' => $competitions,
            'ogMeta' => [
                'title' => 'Competitions - ' . config('app.fullname'),
                'image' => asset('/assets/social.png'),
            ],
        ]);
    }



    public function competition($slug)
    {
        $competition = Competition::where('slug', $slug)
            ->with(['contestants' => function ($query) {
                $query->where('deleted', 0)
                      ->orderByDesc('votes')
                      ->orderByDesc('created_at')
                      ->take(30);
            }])
            ->firstOrFail();

        return Inertia::render('Competition', [
            'competition' => $competition,
            'contestants' => $competition->contestants,
            'ogMeta' => [
                'title' => 'Join the ' . $competition->title .' - '. config('app.fullname'),
                'description' => $competition->description,
                'image' => $competition->cover && Storage::disk('public')->exists($competition->cover)
                    ? asset('storage/' . $competition->cover)
                    : asset('/assets/default_cover.png'),
            ],
        ]);
    }



    public function join($slug)
    {
        $competition = Competition::where('slug', $slug)->firstOrFail()->makeHidden($this->hiddenFields);

        if ($competition->registration_active == 1 && $competition->active == 1) {
            return Inertia::render('JoinCompetition', [
                'competition' => $competition,
                'ogMeta' => [
                    'title' => 'Join the '. $competition->title .' - '. config('app.fullname'),
                    'description' => $competition->description,
                    'image' => $competition->cover && Storage::disk('public')->exists($competition->cover)
                        ? asset('storage/' . $competition->cover)
                        : asset('/assets/default_cover.png'),
                ],
            ]);
        } else {
            return redirect()->route('site.competition', $competition->slug)
                ->with('error',"Registration is closed or competition has ended.");
        } 
    }

    

    public function storeContestant($slug, Request $request)
    {
        $competition = Competition::where('slug', $slug)->firstOrFail();

        if (!$competition) {
            return redirect()->route('home');
        }

        if ($competition->registration_active == 1 && $competition->active == 1) {
            
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'slug' => 'required',
                'email' => 'required|string|max:255',
                'phone' => 'required|string|max:255',
                'insta' => 'required|string|max:255',
                //'age' => 'nullable|integer|min:1',
                //'location' => 'required|string|max:255',
                //'occupation' => 'required|string|max:255',
                'title_of_piece' => 'required|string|max:255',
                'writing_experience' => 'required|string|max:255',
                'discovery_story' => 'required|string',
                'video_path' => 'nullable|file|mimes:mp4,mov|max:51200', // Max ~50MB
                'picture_path' => 'nullable|image|mimes:jpg,jpeg,png|max:3072', // Max 3MB
                'video_path' => 'required|file|mimes:mp4,mov|max:51200', // Max ~50MB
                'picture_path' => 'required|image|mimes:jpg,jpeg,png|max:3072', // Max 3MB
            ], [], [ // ← this third array is for custom attribute names
                'name' => 'Name',
                'email' => 'Email',
                'phone' => 'Telephone No',
                'insta' => 'Instagram Handle',
                //'age' => 'Age',
                //'location' => 'Location',
                //'occupation' => 'Occupation',
                'title_of_piece' => 'Title of Your Piece',
                'writing_experience' => 'Writing Experience',
                'discovery_story' => 'Story of How You Discovered Writing',
                'video_path' => 'Spoken Word Video',
                'picture_path' => 'Profile Picture',
            ]);

            // Store video
            if ($request->hasFile('video_path')) {
                // Initialize image library
                $manager = new ImageManager(new Driver());
                $video = $request->file('video_path');
                $videoName = Str::random(10) . '.' . $video->getClientOriginalExtension();
                $videoPath = "contestants/videos/{$competition->slug}/{$videoName}";
                Storage::disk('public')->put($videoPath, file_get_contents($video));
                $validated['video_path'] = $videoPath;
            }

            if ($request->hasFile('picture_path')) {
                // Initialize image library
                $manager = new ImageManager(new Driver());
                
                $uploadedPic = $request->file('picture_path');

                // Read the image using Intervention Image
                $image = $manager->read($uploadedPic->getRealPath());

                // Check if width exceeds 1200px, and resize if needed
                if ($image->width() > 1200) {
                    // This automatically scales down if wider than 1200px and keeps aspect ratio
                    $image->scaleDown(width: 1200);
                }

                $jpegData = $image->toJpeg(75)->toString();

                $filename = "contestant_" . Str::random(10) . '.jpg';
                $picturePath = "contestants/pics/{$competition->slug}";
                $path = "{$picturePath}/{$filename}";

                Storage::disk('public')->put($path, (string) $jpegData);

                // Save image path to data array
                $validated['picture_path'] = $path;
            }

            //Contestant::create($validated);
            $competition->contestants()->create($validated);

            return redirect()->route('site.competition', $competition->slug)
                ->with('success', "🎉 Thank you for sharing your gift. You've successfully entered the {$competition->title} — may this be the beginning of something incredible. ✨");

        } else {
            return redirect()->route('site.competition', $competition->slug)->with('error',"Registration is closed or competition has ended");
        } 
    }



    public function contestants(Request $request, $slug)
    {
        $competition = Competition::where('slug', $slug)->firstOrFail()->makeHidden($this->hiddenFields);

        // Search term (from query string)
        $search = $request->input('search');

        // Base query for contestants
        $query = $competition->contestants()
            ->where('deleted', 0)
            ->when($search, function ($q) use ($search) {
                $q->where(function ($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%")
                    ->orWhere('title_of_piece', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('votes')
            ->orderBy('created_at');

        $contestants = $query->paginate(30)->withQueryString(); // 30 per page

        // If no contestants, redirect to the competition page
        if ($contestants->total() === 0) {
            return redirect()->route('site.competition', $competition->slug);
        }

        return Inertia::render('Contestants', [
            'competition' => $competition,
            'contestants' => $contestants,
            'filters' => [
                'search' => $search,
            ],
            'ogMeta' => [
                'title' => 'Contestants: '. $competition->title .' - '. config('app.fullname'),
                'description' => $competition->description,
                'image' => $competition->cover && Storage::disk('public')->exists($competition->cover)
                    ? asset('storage/' . $competition->cover)
                    : asset('/assets/default_cover.png'),
            ],
        ]);
    }



    public function contestant(Competition $competition, Contestant $contestant)
    {
        if ($contestant->deleted == 1) {
            abort(404);
        }

        if ($competition->registration_active == 0 && $competition->voting_active == 1) {
            return Inertia::render('Contestant', [
                'contestant' => $contestant,
                'competition' => $competition->makeHidden($this->hiddenFields),
                'shareUrl' => route('site.contestant', [
                    'competition' => $competition->slug,
                    'contestant' => $contestant->slug,
                ]),
                'ogMeta' => [
                    'title' => 'Vote for '. $contestant->name .' - '. config('app.fullname'),
                    'description' => "Cast your vote for ".$contestant->name."'s powerful piece in the ".$competition->title,
                    'image' => $contestant->picture_path && Storage::disk('public')->exists($contestant->picture_path)
                        ? asset('storage/' . $competition->picture_path)
                        : asset('/assets/default_contestant.png'),
                ],
            ]);
        } else {
            return redirect()->route('site.competition', $competition->slug)->with('error', 'Voting is disabled for this competition!');
        }
    }



    public function addVotes(Request $request, Competition $competition, Contestant $contestant)
    {
        // Ensure the contestant belongs to this competition
        if ($contestant->competition_id !== $competition->id) {
            abort(404, 'Contestant does not belong to this competition.');
        }
        
        if ($competition->voting_active == 1 && $competition->active == 1) {
            // Validate votes
            $validated = $request->validate([
                'votes' => 'required|integer|min:1',
            ]);

            $contestant->increment('votes', $validated['votes']);

            return back()->with('success', 'Votes successfully added!');
        } else {
            abort(403, 'Voting is not currently active.');
        }
    }


    public function destroy(Contestant $contestant)
    {
        // Delete contestant files
        if ($contestant->video_path) {
            Storage::disk('public')->delete($contestant->video_path);
        }
        if ($contestant->picture_path) {
            Storage::disk('public')->delete($contestant->picture_path);
        }

        $contestant->delete();

        return back()->with('success', 'Contestant deleted.');
    }



    public function pastWinners()
    {
        $competitions = Competition::where('active', 0)
            ->where('registration_active', 0)
            ->orderBy('created_at', 'desc')->paginate(1);

        return Inertia::render('PastWinners', [
            'competitions' => $competitions,
            'ogMeta' => [
                'title' => 'Past Winners - ' . config('app.fullname'),
                'image' => asset('/assets/social.png'),
            ],
        ]);
    }

    

    public function latest()
    {
        // Get the latest competition (or null if none exists)
        $competition = Competition::latest()->first();

        // If a competition exists, redirect to its page
        if ($competition) {
            $competition->makeHidden($this->hiddenFields);
            return redirect()->route('site.join', $competition->slug);
        }

        // If no competition, redirect back to the previous page
        return redirect()->back();
    }
}
