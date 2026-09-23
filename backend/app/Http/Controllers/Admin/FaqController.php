<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\BuildsAdminDataTables;
use App\Http\Controllers\Admin\Concerns\FlashesMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreFaqRequest;
use App\Http\Requests\Admin\UpdateFaqRequest;
use App\Models\Faq;
use App\Models\Service;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    use BuildsAdminDataTables;
    use FlashesMessages;

    public function index()
    {
        return view('admin.faqs.index', [
            'services' => Service::query()->ordered()->get(['id', 'name']),
            'generalCount' => Faq::query()->general()->count(),
        ]);
    }

    public function data(Request $request)
    {
        $query = Faq::query()
            ->with('service:id,name')
            ->select(['id', 'service_id', 'question', 'answer', 'is_active', 'sort_order'])
            ->when($request->input('scope') === 'general', fn ($builder) => $builder->whereNull('service_id'))
            ->when($request->filled('service_id'), fn ($builder) => $builder->where('service_id', $request->integer('service_id')));

        return $this->adminDataTable($query)
            ->addColumn('faq', fn (Faq $faq) => $this->renderAdminPartial('admin.faqs.partials.question', compact('faq')))
            ->addColumn('scope', fn (Faq $faq) => $this->renderStatusBadge(
                $faq->service?->name ?? 'General',
                $faq->service_id ? 'role' : 'muted',
            ))
            ->editColumn('is_active', fn (Faq $faq) => $this->renderStatusBadge(
                $faq->is_active ? 'Active' : 'Inactive',
                $faq->is_active ? 'active' : 'muted',
            ))
            ->addColumn('actions', fn (Faq $faq) => $this->renderAdminPartial('admin.faqs.partials.actions', compact('faq')))
            ->rawColumns(['faq', 'scope', 'is_active', 'actions'])
            ->toJson();
    }

    public function create()
    {
        $faq = new Faq([
            'is_active' => true,
            'sort_order' => (Faq::query()->max('sort_order') ?? 0) + 1,
        ]);

        return view('admin.faqs.create', $this->formData($faq));
    }

    public function store(StoreFaqRequest $request)
    {
        Faq::create($request->validated());

        return $this->success(redirect()->route('admin.faqs.index'), 'FAQ created successfully.');
    }

    public function edit(Faq $faq)
    {
        return view('admin.faqs.edit', $this->formData($faq));
    }

    public function update(UpdateFaqRequest $request, Faq $faq)
    {
        $faq->update($request->validated());

        return $this->success(redirect()->route('admin.faqs.index'), 'FAQ updated successfully.');
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();

        return $this->success(redirect()->route('admin.faqs.index'), 'FAQ deleted successfully.');
    }

    /**
     * @return array<string, mixed>
     */
    private function formData(Faq $faq): array
    {
        return [
            'faq' => $faq,
            'services' => Service::query()->ordered()->get(['id', 'name']),
        ];
    }
}
