<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use Inertia\Inertia;

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
        ]);
    }


    public function competitions()
    {
        $competitions = Competition::orderBy('created_at', 'desc')
            ->paginate(10)
            ->through(fn ($item) => $item->makeHidden($this->hiddenFields));

        return Inertia::render('Competitions', [
            'competitions' => $competitions,
        ]);
    }


    public function competition($slug)
    {
        $competition = Competition::where('slug', $slug)->firstOrFail();

        return Inertia::render('Competition', [
            'competition' => $competition,
        ]);
    }

    
    public function latest()
    {
        // Get the latest competition (or null if none exists)
        $competition = Competition::latest()->first();

        // If a competition exists, redirect to its page
        if ($competition) {
            $competition->makeHidden($this->hiddenFields);
            return redirect()->route('site.competition', $competition->slug);
        }

        // If no competition, redirect back to the previous page
        return redirect()->back();
    }
}
