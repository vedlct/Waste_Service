<div>
    <div class="fw-bold">{{ \App\Support\Money::format($item->price_pence) }}</div>
    <div class="small text-muted">
        {{ \App\Support\Money::format($item->price_ex_vat_pence) }} ex VAT
        &middot; {{ \App\Support\Money::formatVatRate($item->vat_rate_basis_points) }}
    </div>
</div>
