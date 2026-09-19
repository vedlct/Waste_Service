<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreReviewRequest;
use App\Http\Resources\Api\V1\ReviewResource;
use App\Models\Review;
use App\Support\WebsiteNotifier;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $reviews = Review::query()
            ->published()
            ->with('service:id,slug,name')
            ->when($request->filled('service'), fn ($query) => $query->whereHas(
                'service',
                fn ($builder) => $builder->where('slug', $request->string('service')->toString()),
            ))
            ->when($request->filled('min_rating'), fn ($query) => $query->where('rating', '>=', $request->integer('min_rating')))
            ->latestFirst()
            ->limit(min(50, max(1, (int) $request->integer('limit', 12))))
            ->get();

        return ReviewResource::collection($reviews);
    }

    /**
     * Accepts a review from the public "write a review" form. It is stored as pending and
     * only reaches the website once a moderator publishes it.
     */
    public function store(StoreReviewRequest $request)
    {
        $review = Review::create($request->reviewAttributes());

        WebsiteNotifier::reviewReceived($review);

        return response()->json([
            'ok' => true,
            'message' => 'Thanks for your review. It will appear on the website once our team has checked it.',
        ], 201);
    }
}
