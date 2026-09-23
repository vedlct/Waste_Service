<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\BuildsAdminDataTables;
use App\Http\Controllers\Admin\Concerns\FlashesMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateContactEnquiryRequest;
use App\Models\ContactEnquiry;
use App\Models\User;
use Illuminate\Http\Request;

class ContactEnquiryController extends Controller
{
    use BuildsAdminDataTables;
    use FlashesMessages;

    public function index()
    {
        return view('admin.enquiries.index', [
            'assignees' => $this->assignees(),
            'openCount' => ContactEnquiry::query()->open()->count(),
            'unassignedOpenCount' => ContactEnquiry::query()->open()->unassigned()->count(),
        ]);
    }

    public function data(Request $request)
    {
        $query = ContactEnquiry::query()
            ->with(['service:id,name', 'assignee:id,name'])
            ->select(['id', 'service_id', 'name', 'email', 'phone', 'service_label', 'message', 'status', 'source', 'assigned_to', 'responded_at', 'created_at'])
            ->when($request->filled('status'), fn ($builder) => $builder->where('status', $request->string('status')->toString()))
            ->when($request->input('scope') === 'open', fn ($builder) => $builder->open())
            ->when($request->input('scope') === 'unassigned', fn ($builder) => $builder->open()->unassigned())
            ->when($request->filled('assigned_to'), fn ($builder) => $builder->where('assigned_to', $request->integer('assigned_to')));

        return $this->adminDataTable($query)
            // Replaces the default column search, so an email or phone number finds the enquiry.
            ->filter(fn ($builder) => $builder->search((string) $request->input('search.value', '')))
            ->addColumn('contact', fn (ContactEnquiry $enquiry) => $this->renderAdminPartial('admin.enquiries.partials.contact', compact('enquiry')))
            ->addColumn('enquiry', fn (ContactEnquiry $enquiry) => $this->renderAdminPartial('admin.enquiries.partials.summary', compact('enquiry')))
            ->editColumn('status', fn (ContactEnquiry $enquiry) => $this->renderAdminPartial('admin.enquiries.partials.status', compact('enquiry')))
            ->editColumn('source', fn (ContactEnquiry $enquiry) => $enquiry->sourceLabel())
            ->addColumn('assignee', fn (ContactEnquiry $enquiry) => $enquiry->assignee?->name ?? 'Unassigned')
            ->editColumn('created_at', fn (ContactEnquiry $enquiry) => $this->formatAdminDate($enquiry->created_at, 'd M Y, h:i A'))
            ->addColumn('actions', fn (ContactEnquiry $enquiry) => $this->renderAdminPartial('admin.enquiries.partials.actions', compact('enquiry')))
            ->rawColumns(['contact', 'enquiry', 'status', 'actions'])
            ->toJson();
    }

    public function show(ContactEnquiry $enquiry)
    {
        return view('admin.enquiries.show', [
            'enquiry' => $enquiry->load(['service:id,name', 'assignee:id,name']),
            'assignees' => $this->assignees(),
        ]);
    }

    public function update(UpdateContactEnquiryRequest $request, ContactEnquiry $enquiry)
    {
        $data = $request->validated();

        $enquiry->update($data + $enquiry->respondedTimestamp($data['status']));

        return $this->success(
            redirect()->route('admin.enquiries.show', $enquiry),
            'Enquiry updated successfully.',
        );
    }

    public function destroy(ContactEnquiry $enquiry)
    {
        // Soft deleted so a spam clear-out stays reversible.
        $enquiry->delete();

        return $this->success(redirect()->route('admin.enquiries.index'), 'Enquiry deleted successfully.');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, User>
     */
    private function assignees()
    {
        return User::query()
            ->where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name']);
    }
}
