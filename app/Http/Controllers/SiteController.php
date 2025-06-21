<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use App\Models\Contestant;
use App\Models\VoteTransaction;
use Inertia\Inertia;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

use Illuminate\Http\Request;

class SiteController extends Controller
{
    // These fields will be hidden when showing competitions publicly
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





    // Show the homepage with the latest 10 competitions
    public function home()
    {
        $competitions = Competition::latest()->limit(10)->get()->makeHidden($this->hiddenFields);

        return Inertia::render('Index', [
            'competitions' => $competitions,
            'ogMeta' => [ // Meta tags for SEO / social sharing
                'title' => 'Home - ' . config('app.fullname'),
                'image' => asset('/assets/social.png'),
            ],
        ]);
    }

    



    // Show paginated list of all competitions (newest first)
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

    



    // Show a specific competition and its top 30 contestants
    public function competition($slug)
    {
        $competition = Competition::where('slug', $slug)
            ->with(['contestants' => function ($query) {
                // Only show contestants not marked as deleted
                $query->where('deleted', 0)
                      ->orderByDesc('votes') // Sort by votes, then by latest
                      ->orderByDesc('created_at')
                      ->take(30); // Show top 30
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
            'jsonLd' => [
                '@context' => 'https://schema.org',
                '@type' => 'Event',
                'name' => $$competition->title,
                'url' => url("/competitions/{$competition->slug}"),
                'image' => asset("storage/{$competition->cover}"),
                'description' => $competition->description,
            ],
        ]);
    }

    



    // Show the join form for an active and open competition
    public function join($slug)
    {
        $competition = Competition::where('slug', $slug)->firstOrFail()->makeHidden($this->hiddenFields);

        // Only allow join if registration and competition are active
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
            // Redirect if registration is closed
            return redirect()->route('site.competition', $competition->slug)
                ->with('error',"Registration is closed or competition has ended.");
        } 
    }

    



    // Handle submission of contestant registration form
    public function storeContestant($slug, Request $request)
    {
        $competition = Competition::where('slug', $slug)->firstOrFail();

        // Only allow submission if registration is open
        if ($competition->registration_active == 1 && $competition->active == 1) {
            
            // Validate form inputs (with readable names for error messages)
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'slug' => 'required',
                'email' => 'required|string|max:255',
                'phone' => 'required|string|max:255',
                'insta' => 'required|string|max:255',
                'title_of_piece' => 'required|string|max:255',
                'writing_experience' => 'required|string|max:255',
                'discovery_story' => 'required|string',
                'video_path' => 'required|file|mimes:mp4,mov|max:51200',
                'picture_path' => 'required|image|mimes:jpg,jpeg,png|max:3072',
            ], [], [
                'name' => 'Name',
                'email' => 'Email',
                'phone' => 'Telephone No',
                'insta' => 'Instagram Handle',
                'title_of_piece' => 'Title of Your Piece',
                'writing_experience' => 'Writing Experience',
                'discovery_story' => 'Story of How You Discovered Writing',
                'video_path' => 'Spoken Word Video',
                'picture_path' => 'Profile Picture',
            ]);

            // Store video in public disk
            if ($request->hasFile('video_path')) {
                $video = $request->file('video_path');
                $videoName = Str::random(10) . '.' . $video->getClientOriginalExtension();
                $videoPath = "contestants/videos/{$competition->slug}/{$videoName}";
                Storage::disk('public')->put($videoPath, file_get_contents($video));
                $validated['video_path'] = $videoPath;
            }

            // Process and store profile picture
            if ($request->hasFile('picture_path')) {
                $manager = new ImageManager(new Driver());
                $uploadedPic = $request->file('picture_path');
                $image = $manager->read($uploadedPic->getRealPath());

                // Resize if wider than 1200px
                if ($image->width() > 1200) {
                    $image->scaleDown(width: 1200);
                }

                $jpegData = $image->toJpeg(75)->toString();
                $filename = "contestant_" . Str::random(10) . '.jpg';
                $path = "contestants/pics/{$competition->slug}/{$filename}";
                Storage::disk('public')->put($path, (string) $jpegData);
                $validated['picture_path'] = $path;
            }

            // Save new contestant record linked to the competition
            $competition->contestants()->create($validated);

            return redirect()->route('site.competition', $competition->slug)
                ->with('success', "🎉 You've successfully entered the {$competition->title}! ✨");

        } else {
            return redirect()->route('site.competition', $competition->slug)->with('error',"Registration is closed or competition has ended");
        } 
    }

    



    // Show a list of contestants for a competition (with search)
    public function contestants(Request $request, $slug)
    {
        $competition = Competition::where('slug', $slug)->firstOrFail()->makeHidden($this->hiddenFields);

        $search = $request->input('search');

        $query = $competition->contestants()
            ->where('deleted', 0)
            ->when($search, function ($q) use ($search) {
                // Filter by name or title
                $q->where(function ($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%")
                        ->orWhere('title_of_piece', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('votes')
            ->orderBy('created_at');

        $contestants = $query->paginate(30)->withQueryString(); // keep search query in pagination

        // If none found, redirect back
        if ($contestants->total() === 0) {
            return redirect()->route('site.competition', $competition->slug);
        }

        return Inertia::render('Contestants', [
            'competition' => $competition,
            'contestants' => $contestants,
            'filters' => ['search' => $search],
            'ogMeta' => [
                'title' => 'Contestants: '. $competition->title .' - '. config('app.fullname'),
                'description' => $competition->description,
                'image' => $competition->cover && Storage::disk('public')->exists($competition->cover)
                    ? asset('storage/' . $competition->cover)
                    : asset('/assets/default_cover.png'),
            ],
        ]);
    }

    



    // Show individual contestant's page
    public function contestant(Competition $competition, Contestant $contestant)
    {
        if ($contestant->deleted == 1) {
            abort(404); // prevent access to deleted profiles
        }

        // Show page only if voting is active
        if ($competition->registration_active == 0 && $competition->voting_active == 1) {
            $description = "Vote for ".$contestant->name."'s piece in ".$competition->title;

            return Inertia::render('Contestant', [
                'contestant' => $contestant,
                'competition' => $competition->makeHidden($this->hiddenFields),
                'shareUrl' => route('site.contestant', [
                    'competition' => $competition->slug,
                    'contestant' => $contestant->slug,
                ]),
                'ogMeta' => [
                    'title' => 'Vote for '. $contestant->name .' - '. config('app.fullname'),
                    'description' => $description,
                    'image' => $contestant->picture_path && Storage::disk('public')->exists($contestant->picture_path)
                        ? asset('storage/' . $contestant->picture_path)
                        : asset('/assets/default_contestant.png'),
                ],
                'jsonLd' => [
                    [
                        '@context' => 'https://schema.org',
                        '@type' => 'Person',
                        'name' => $contestant->name,
                        'url' => route('site.contestant', [
                            'competition' => $competition->slug,
                            'contestant' => $contestant->slug,
                        ]),
                        'image' => asset("storage/{$contestant->picture_path}"),
                        'description' => $description,
                    ],
                    [
                        '@context' => 'https://schema.org',
                        '@type' => 'VideoObject',
                        'name' => "{$contestant->name} Contest Video",
                        'description' => $description,
                        'thumbnailUrl' => asset("storage/{$contestant->picture_path}"),
                        'uploadDate' => $contestant->created_at->toDateString(),
                        'contentUrl' => asset("storage/{$contestant->video_path}"),
                    ]
                ]
            ]);
        } else {
            return redirect()->route('site.competition', $competition->slug)
                ->with('error', 'Voting is disabled for this competition!');
        }
    }

    



    // Start a payment for voting using Paystack
    public function addVotes(Request $request, Competition $competition, Contestant $contestant)
    {
        if ($contestant->competition_id !== $competition->id) {
            abort(404, 'Invalid contestant.');
        }

        if ($competition->voting_active != 1 || $competition->active != 1) {
            abort(403, 'Voting is not active.');
        }

        $validated = $request->validate([
            'votes' => 'required|integer|min:1',
            'amount' => 'required|integer|min:100', // amount in kobo
            'email' => 'required|email',
            'phone' => 'required',
            'prev_votes' => 'required'
        ]);

        $reference = Str::uuid()->toString(); // Unique transaction reference

        // Initialize payment
        $response = Http::withToken(env('PAYSTACK_SECRET_KEY'))->post('https://api.paystack.co/transaction/initialize', [
            'email' => $validated['email'],
            'amount' => $validated['amount'],
            'reference' => $reference,
            'callback_url' => route('paystack.verify'),
            'metadata' => array_merge($validated, [
                'contestant_id' => $contestant->id,
                'competition_id' => $competition->id,
            ])
        ]);

        if ($response->successful() && isset($response['data']['authorization_url'])) {
            return response()->json([
                'redirect_url' => $response['data']['authorization_url'],
            ]);
        }

        return response()->json([
            'error' => 'Unable to initiate payment. Please try again.',
        ], 500);
    }

    



    // Handle Paystack payment verification
    public function verifyPaystack(Request $request)
    {
        $reference = $request->query('reference');

        if (!$reference) {
            return redirect('/')->withErrors('No payment reference provided.');
        }

        $response = Http::withToken(env('PAYSTACK_SECRET_KEY'))->get("https://api.paystack.co/transaction/verify/{$reference}");

        if ($response->successful() && $response['data']['status'] === 'success') {
            $meta = $response['data']['metadata'];
            $contestant = Contestant::find($meta['contestant_id']);
            $competition = Competition::find($meta['competition_id']);

            // If all data is valid, update vote count
            if ($contestant && $competition && $contestant->competition_id === $competition->id) {
                $contestant->increment('votes', $meta['votes']);

                // Save transaction for tracking
                $contestant->voteTransactions()->create([
                    'amount' => $meta['amount'],
                    'votes' => $meta['votes'],
                    'prev_votes' => $meta['prev_votes'],
                    'email' => $meta['email'],
                    'phone' => $meta['phone'] ?? null,
                ]);

                return redirect()->route('site.contestant', [
                    'competition' => $competition->slug,
                    'contestant' => $contestant->slug,
                ])->with('success', 'Vote successfully added!');
            }
        }

        return redirect('/')->withErrors('Payment verification failed or was cancelled.');
    }

    



    // Soft-delete contestant (and remove files)
    public function destroy(Contestant $contestant)
    {
        // Delete video and image if they exist
        if ($contestant->video_path) {
            Storage::disk('public')->delete($contestant->video_path);
        }
        if ($contestant->picture_path) {
            Storage::disk('public')->delete($contestant->picture_path);
        }

        $contestant->delete(); // soft delete

        return back()->with('success', 'Contestant deleted.');
    }

    



    // Show list of past competitions (that have ended)
    public function pastWinners()
    {
        $competitions = Competition::where('active', 0)
            ->where('registration_active', 0)
            ->orderBy('created_at', 'desc')
            ->paginate(1);

        return Inertia::render('PastWinners', [
            'competitions' => $competitions,
            'ogMeta' => [
                'title' => 'Past Winners - ' . config('app.fullname'),
                'image' => asset('/assets/social.png'),
            ],
        ]);
    }

    



    // Redirect user to the latest competition's join page
    public function latest()
    {
        $competition = Competition::latest()->first();

        if ($competition) {
            $competition->makeHidden($this->hiddenFields);
            return redirect()->route('site.join', $competition->slug);
        }

        return redirect()->back();
    }
}
