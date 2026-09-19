<div>
    <div class="fw-bold">{{ \App\Support\Money::format($package->price_inc_vat_pence) }}</div>
    <div class="small text-muted">
        {{ \App\Support\Money::format($package->price_ex_vat_pence) }} ex VAT
        &middot; {{ \App\Support\Money::formatVatRate($package->vat_rate_basis_points) }}
    </div>
</div>
