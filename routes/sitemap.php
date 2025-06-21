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
        Url::create('/terms')
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            ->setPriority(0.3)
    );

    // ✅ Dynamic Competition Pages
    Competition::all()->each(function ($competition) use ($sitemap) {
        $sitemap->add(
            Url::create("/competitions/{$competition->slug}")
                ->setLastModificationDate($competition->updated_at ?? Carbon::now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(0.8)
        );
    });

    // ✅ Dynamic Contestant Pages
    Contestant::all()->each(function ($contestant) use ($sitemap) {
        $sitemap->add(
            Url::create("/contestants/{$contestant->slug}")
                ->setLastModificationDate($contestant->updated_at ?? Carbon::now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(0.6)
        );
    });

    return $sitemap->toResponse(request());
});
