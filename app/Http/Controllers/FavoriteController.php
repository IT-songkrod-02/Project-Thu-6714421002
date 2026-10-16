<?php

namespace App\Http\Controllers;

use App\Models\Reel;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index(Request $request)
    {
        $reels = $request->user()->favorites()->with(['brand', 'primaryImage', 'user'])
            ->latest('favorites.created_at')->paginate(12);
        $favoriteIds = $reels->pluck('id')->all();
        return view('favorites.index', compact('reels', 'favoriteIds'));
    }

    public function toggle(Request $request, Reel $reel)
    {
        $request->user()->favorites()->toggle($reel->id);
        return back();
    }
}