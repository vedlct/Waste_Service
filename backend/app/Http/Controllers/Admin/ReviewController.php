<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\BuildsAdminDataTables;
use App\Http\Controllers\Admin\Concerns\FlashesMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ModerateReviewRequest;
use App\Http\Requests\Admin\StoreReviewRequest;
use App\Http\Requests\Admin\UpdateReviewRequest;
use App\Models\Review;
use App\Models\Service;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    use BuildsAdminDataTables;
    use FlashesMessages;

    public function index()
    {
        return view('admin.reviews.index', [
            'services' => Service::query()->ordered()->get(['id', 'name']),
            'pendingCount' => Review::query()->awaitingModeration()->count(),
            'publishedCount' => Review::query()->published()->count(),
        ]);
    }

    public function data(Request $request)
    {
        $query = Review::query()
            ->with('service:id,name')
            ->select(['id', 'service_id', 'reviewer_name', 'reviewer_email', 'rating', 'body', 'source', 'status', 'reviewed_at', 'published_at', 'created_at'])
            ->when($request->filled('status'), fn ($builder) => $builder->where('status', $request->string('status')->toString()))
            ->when($request->filled('service_id'), fn ($builder) => $builder->where('service_id', $request->integer('service_id')))
            ->when($request->filled('rating'), fn ($builder) => $builder->where('rating', $request->integer('rating')));

        return $this->adminDataTable($query)
            ->addColumn('reviewer', fn (Review $review) => $this->renderAdminPartial('admin.reviews.partials.reviewer', compact('review')))
            ->editColumn('rating', fn (Review $review) => $this->renderAdminPartial('admin.partials.rating-stars', ['rating' => $review->rating]))
            ->editColumn('body', fn (Review $review) => e(str($review->body)->limit(120)))
            ->addColumn('service', fn (Review $review) => $review->service?->name ?? 'General')
            ->editColumn('source', fn (Review $review) => $review->sourceLabel())
            ->editColumn('status', fn (Review $review) => $this->renderAdminPartial('admin.reviews.partials.status', compact('review')))
            ->editColumn('published_at', fn (Review $review) => $this->formatAdminDate($review->published_at, 'd M Y', 'Not published'))
            ->addColumn('actions', fn (Review $review) => $this->renderAdminPartial('admin.reviews.partials.actions', compact('review')))
            ->rawColumns(['reviewer', 'rating', 'status', 'actions'])
            ->toJson();
    }

    public function create()
    {
        $review = new Review([
            'rating' => config('reviews.max_rating', 5),
            'source' => 'manual',
            'status' => 'published',
        ]);

        return view('admin.reviews.create', $this->formData($review));
    }

    public function store(StoreReviewRequest $request)
    {
        $data = $request->validated();
        $review = new Review($data);

        $review->fill($review->moderationTimestamps($data['status']))->save();

        return $this->success(redirect()->route('admin.reviews.index'), 'Review created successfully.');
    }

    public function edit(Review $review)
    {
        return view('admin.reviews.edit', $this->formData($review));
    }

    public function update(UpdateReviewRequest $request, Review $review)
    {
        $data = $request->validated();

        $review->update($data + $review->moderationTimestamps($data['status']));

        return $this->success(redirect()->route('admin.reviews.index'), 'Review updated successfully.');
    }

    /**
     * Quick approve/reject straight from the moderation list.
     */
    public function moderate(ModerateReviewRequest $request, Review $review)
    {
        $status = $request->validated()['status'];

        $review->update(['status' => $status] + $review->moderationTimestamps($status));

        return $this->success(
            redirect()->route('admin.reviews.index'),
            'Review marked as '.$review->statusLabel().'.',
        );
    }

    public function destroy(Review $review)
    {
        $review->delete();

        return $this->success(redirect()->route('admin.reviews.index'), 'Review deleted successfully.');
    }

    /**
     * @return array<string, mixed>
     */
    private function formData(Review $review): array
    {
        return [
            'review' => $review,
            'services' => Service::query()->ordered()->get(['id', 'name']),
        ];
    }
}
