<?php
namespace App\Http\Controllers;

use App\Models\Competition;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class CompetitionController extends Controller
{
    public function index()
    {
        $competitions = Competition::orderBy('created_at', 'desc')->paginate(20);

        return Inertia::render('Dashboard', [
            'competitions' => $competitions,
        ]);
    }

    public function show($slug)
    {
        $competition = Competition::where('slug', $slug)->firstOrFail();

        return Inertia::render('dashboard/Competition', [
            'competition' => $competition,
        ]);
    }
    
}
?>