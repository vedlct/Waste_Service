<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\BuildsAdminDataTables;
use App\Http\Controllers\Admin\Concerns\FlashesMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreExtraChargeRequest;
use App\Http\Requests\Admin\UpdateExtraChargeRequest;
use App\Models\ExtraCharge;
use App\Support\Money;

class ExtraChargeController extends Controller
{
    use BuildsAdminDataTables;
    use FlashesMessages;

    public function index()
    {
        return view('admin.extra-charges.index');
    }

    public function data()
    {
        return $this->adminDataTable(
            ExtraCharge::query()->select([
                'id', 'slug', 'name', 'description', 'amount_pence', 'is_variable',
                'charge_type', 'pricing_status', 'is_active', 'sort_order',
            ])
        )
            ->addColumn('charge', fn (ExtraCharge $charge) => $this->renderAdminPartial('admin.extra-charges.partials.name', compact('charge')))
            ->editColumn('amount_pence', fn (ExtraCharge $charge) => $charge->is_variable
                ? '<span class="text-muted">Quoted per job</span>'
                : '<span class="fw-bold">'.e(Money::format($charge->amount_pence)).'</span>')
            ->editColumn('charge_type', fn (ExtraCharge $charge) => config('pricing.charge_types.'.$charge->charge_type, str($charge->charge_type)->headline()))
            ->editColumn('pricing_status', fn (ExtraCharge $charge) => $this->renderAdminPartial('admin.partials.pricing-status-badge', ['status' => $charge->pricing_status]))
            ->editColumn('is_active', fn (ExtraCharge $charge) => $this->renderStatusBadge(
                $charge->is_active ? 'Active' : 'Inactive',
                $charge->is_active ? 'active' : 'muted',
            ))
            ->addColumn('actions', fn (ExtraCharge $charge) => $this->renderAdminPartial('admin.extra-charges.partials.actions', compact('charge')))
            ->rawColumns(['charge', 'amount_pence', 'pricing_status', 'is_active', 'actions'])
            ->toJson();
    }

    public function create()
    {
        $charge = new ExtraCharge([
            'charge_type' => 'fixed',
            'pricing_status' => 'active',
            'is_variable' => false,
            'is_active' => true,
            'sort_order' => 0,
        ]);

        return view('admin.extra-charges.create', $this->formData($charge));
    }

    public function store(StoreExtraChargeRequest $request)
    {
        ExtraCharge::create($request->extraChargeAttributes());

        return $this->success(redirect()->route('admin.extra-charges.index'), 'Extra charge created successfully.');
    }

    public function edit(ExtraCharge $extraCharge)
    {
        return view('admin.extra-charges.edit', $this->formData($extraCharge));
    }

    public function update(UpdateExtraChargeRequest $request, ExtraCharge $extraCharge)
    {
        $extraCharge->update($request->extraChargeAttributes());

        return $this->success(redirect()->route('admin.extra-charges.index'), 'Extra charge updated successfully.');
    }

    public function destroy(ExtraCharge $extraCharge)
    {
        $extraCharge->delete();

        return $this->success(redirect()->route('admin.extra-charges.index'), 'Extra charge deleted successfully.');
    }

    /**
     * @return array<string, mixed>
     */
    private function formData(ExtraCharge $charge): array
    {
        return [
            'charge' => $charge,
            'amountPounds' => Money::toPounds($charge->amount_pence),
        ];
    }
}
