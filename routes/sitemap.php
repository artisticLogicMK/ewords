<?php
use Illuminate\Support\Facades\Route;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\Competition;
use App\Models\Contestant;
use Carbon\Carbon;

Route::get('/sitemap.xml', function () {
    $sitemap = Sitemap::create();

    // ✅ Static Pages
    $sitemap->add(
        Url::create('/')
            ->setLastModificationDate(Carbon::now())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            ->setPriority(1.0)
    );

    $sitemap->add(
        Url::create('/competitions')
            ->setLastModificationDate(Carbon::now())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            ->setPriority(0.9)
    );

    $sitemap->add(
        Url::create('/pastwinners')
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            ->setPriority(0.5)
    );

    $sitemap->add(
        Url::create('/latest')
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            ->setPriority(0.7)
    );

    $sitemap->add(
        Url::create('/terms')
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            ->setPriority(0.3)
    );



    // ✅ Only the most recent competition
    $latestCompetition = Competition::latest('created_at')->first();

    if ($latestCompetition) {
        $sitemap->add(
            Url::create("/competitions/{$latestCompetition->slug}")
                ->setLastModificationDate($latestCompetition->updated_at ?? Carbon::now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(0.8)
        );
    }



    // ✅ Dynamic Contestant Pages
    Contestant::with('competition')->get()->each(function ($contestant) use ($sitemap) {
        // Skip if competition is missing (to avoid broken route generation)
        if (!$contestant->competition) {
            return;
        }

        $sitemap->add(
            Url::create(
                route('site.contestant', [
                    'competition' => $contestant->competition->slug,
                    'contestant' => $contestant->slug,
                ])
            )
            ->setLastModificationDate($contestant->updated_at ?? Carbon::now())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            ->setPriority(0.6)
        );
    });


    return $sitemap->toResponse(request());
});
