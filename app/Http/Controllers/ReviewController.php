<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, User $seller)
    {
        abort_if($seller->id === $request->user()->id, 403, 'ไม่สามารถรีวิวตัวเองได้');

        $data = $request->validate([
            'rating' => ['required', 'integer', 'between:1,5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        Review::updateOrCreate(
            ['seller_id' => $seller->id, 'reviewer_id' => $request->user()->id],
            $data
        );

        $seller->update(['seller_rating' => round($seller->reviewsReceived()->avg('rating'), 2)]);

        return back()->with('success', 'ขอบคุณสำหรับรีวิว');
    }
}