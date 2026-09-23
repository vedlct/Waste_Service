<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') | {{ config('app.name', 'MR. TEE') }}</title>
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/v/bs5/dt-3.0.4/datatables.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <style>
        :root { --tee-navy:#11224d; --tee-blue:#0497e2; --tee-amber:#f4b942; --tee-soft:#f3f6f7; --tee-line:#dbe8f2; }
        body { background:var(--tee-soft); color:var(--tee-navy); font-family:Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; }
        .fw-black { font-weight: 900; }
        .admin-shell { min-height:100vh; display:grid; grid-template-columns:17rem minmax(0, 1fr); }
        .admin-sidebar { background:#0d1b3f; color:#fff; position:sticky; top:0; height:100vh; padding:1.25rem; overflow-y:auto; }
        .sidebar-heading { margin:1.1rem .9rem .2rem; font-size:.7rem; font-weight:800; letter-spacing:.12em; text-transform:uppercase; color:rgba(255,255,255,.4); }
        .brand-card { background:rgba(255,255,255,.08); border:1px solid rgba(255,255,255,.12); border-radius:1rem; padding:1rem; }
        .brand-logo { max-height:44px; width:auto; }
        .sidebar-link { display:flex; align-items:center; gap:.75rem; color:rgba(255,255,255,.72); border-radius:.9rem; padding:.8rem .9rem; text-decoration:none; font-weight:700; margin-top:.35rem; transition:.2s ease; }
        .sidebar-link:hover, .sidebar-link.active { color:#fff; background:rgba(4,151,226,.22); }
        .sidebar-link.active i { color:var(--tee-amber); }
        .admin-main { min-width:0; }
        .topbar { background:rgba(255,255,255,.82); backdrop-filter:blur(18px); border-bottom:1px solid var(--tee-line); position:sticky; top:0; z-index:20; }
        .admin-breadcrumbs { padding:1rem 1.5rem 0; }
        .admin-breadcrumbs .breadcrumb { --bs-breadcrumb-divider-color:#8b96a9; font-size:.875rem; font-weight:700; }
        .admin-breadcrumbs a { color:var(--tee-blue); text-decoration:none; }
        .admin-breadcrumbs a:hover { color:var(--tee-navy); }
        .admin-breadcrumbs .active { color:#6d7483; }
        .content-wrap { padding:1.5rem; }
        .page-panel { background:#fff; border:1px solid rgba(17,34,77,.08); border-radius:1.25rem; box-shadow:0 24px 60px rgba(17,34,77,.08); }
        .metric-card { background:#fff; border:1px solid rgba(17,34,77,.08); border-radius:1.1rem; padding:1.1rem; min-height:9rem; box-shadow:0 16px 36px rgba(17,34,77,.06); }
        .metric-icon { width:2.75rem; height:2.75rem; border-radius:.9rem; display:grid; place-items:center; color:var(--tee-blue); background:rgba(4,151,226,.1); }
        .image-preview-frame { aspect-ratio:16/10; border:1px dashed #b8c9d8; border-radius:1rem; background:#f8fafc; display:grid; place-items:center; overflow:hidden; }
        .image-preview-frame img { width:100%; height:100%; object-fit:cover; }
        .media-thumb { width:4.5rem; aspect-ratio:4/3; border:1px solid #dbe8f2; border-radius:.85rem; background:#f8fafc; display:grid; place-items:center; overflow:hidden; }
        .media-thumb img { width:100%; height:100%; object-fit:cover; }
        .media-option-thumb { width:2.5rem; height:2rem; border-radius:.5rem; background:#f1f5f9; display:inline-grid; place-items:center; overflow:hidden; flex:0 0 auto; }
        .media-option-thumb img { width:100%; height:100%; object-fit:cover; }
        .select2-container--default .select2-results__option { display:flex; align-items:center; }
        .btn-tee { --bs-btn-bg:var(--tee-navy); --bs-btn-border-color:var(--tee-navy); --bs-btn-color:#fff; --bs-btn-hover-bg:var(--tee-blue); --bs-btn-hover-border-color:var(--tee-blue); --bs-btn-hover-color:#fff; border-radius:.8rem; font-weight:800; }
        .btn-outline-tee { --bs-btn-color:var(--tee-navy); --bs-btn-border-color:rgba(17,34,77,.2); --bs-btn-hover-bg:var(--tee-navy); --bs-btn-hover-border-color:var(--tee-navy); --bs-btn-hover-color:#fff; border-radius:.8rem; font-weight:800; }
        .status-pill { display:inline-flex; align-items:center; gap:.35rem; border-radius:999px; padding:.35rem .65rem; font-size:.75rem; font-weight:800; }
        .status-pill.active { color:#107044; background:#e9f8f0; }
        .status-pill.muted { color:#6c7280; background:#f1f3f6; }
        .status-pill.role { color:#8a5b00; background:#fff5d9; }
        .table > :not(caption) > * > * { padding:1rem .85rem; vertical-align:middle; }
        .dataTables_wrapper .form-control, .dataTables_wrapper .form-select, .form-control, .form-select, .select2-container--default .select2-selection--single { border-radius:.8rem; border-color:#d8e3ed; min-height:2.8rem; }
        .select2-container--default .select2-selection--single .select2-selection__rendered { line-height:2.8rem; }
        .select2-container--default .select2-selection--single .select2-selection__arrow { height:2.8rem; }
        @media (max-width:991.98px) { .admin-shell { display:block; } .admin-sidebar { position:relative; height:auto; border-radius:0 0 1.25rem 1.25rem; } }
    </style>
    @stack('styles')
</head>
<body>
    @auth
        <div class="admin-shell">
            <aside class="admin-sidebar">
                <div class="brand-card">
                    <img class="brand-logo" src="{{ asset('images/MainLogo.png') }}" alt="MR. TEE">
                    <div class="mt-3 small text-white-50">Admin workspace</div>
                </div>
                @php($navigation = [
                    ['heading' => null, 'links' => [
                        ['route' => 'admin.dashboard', 'active' => 'admin.dashboard', 'icon' => 'grid-1x2-fill', 'label' => 'Dashboard', 'module' => null],
                    ]],
                    ['heading' => 'Operations', 'links' => [
                        ['route' => 'admin.bookings.index', 'active' => 'admin.bookings.*', 'icon' => 'calendar-check', 'label' => 'Bookings', 'module' => 'bookings'],
                        ['route' => 'admin.enquiries.index', 'active' => 'admin.enquiries.*', 'icon' => 'envelope-paper', 'label' => 'Enquiries', 'module' => 'enquiries'],
                    ]],
                    ['heading' => 'Content', 'links' => [
                        ['route' => 'admin.services.index', 'active' => 'admin.services.*', 'icon' => 'boxes', 'label' => 'Services', 'module' => 'services'],
                        ['route' => 'admin.service-categories.index', 'active' => 'admin.service-categories.*', 'icon' => 'diagram-3', 'label' => 'Service Categories', 'module' => 'services'],
                        ['route' => 'admin.pages.index', 'active' => 'admin.pages.*', 'icon' => 'file-earmark-text', 'label' => 'Pages & SEO', 'module' => 'pages'],
                        ['route' => 'admin.faqs.index', 'active' => 'admin.faqs.*', 'icon' => 'patch-question', 'label' => 'FAQs', 'module' => 'faqs'],
                        ['route' => 'admin.reviews.index', 'active' => 'admin.reviews.*', 'icon' => 'star', 'label' => 'Reviews', 'module' => 'reviews'],
                        ['route' => 'admin.coverage-regions.index', 'active' => 'admin.coverage-regions.*', 'icon' => 'map', 'label' => 'Coverage Regions', 'module' => 'coverage'],
                        ['route' => 'admin.coverage-areas.index', 'active' => 'admin.coverage-areas.*', 'icon' => 'geo-alt', 'label' => 'Coverage Areas', 'module' => 'coverage'],
                        ['route' => 'admin.media.index', 'active' => 'admin.media.*', 'icon' => 'images', 'label' => 'Media', 'module' => 'media'],
                    ]],
                    ['heading' => 'Pricing', 'links' => [
                        ['route' => 'admin.price-categories.index', 'active' => 'admin.price-categories.*', 'icon' => 'tags', 'label' => 'Price Categories', 'module' => 'pricing'],
                        ['route' => 'admin.service-items.index', 'active' => 'admin.service-items.*', 'icon' => 'list-ul', 'label' => 'Service Items', 'module' => 'pricing'],
                        ['route' => 'admin.load-packages.index', 'active' => 'admin.load-packages.*', 'icon' => 'truck', 'label' => 'Load Packages', 'module' => 'pricing'],
                        ['route' => 'admin.extra-charges.index', 'active' => 'admin.extra-charges.*', 'icon' => 'plus-circle', 'label' => 'Extra Charges', 'module' => 'pricing'],
                    ]],
                    ['heading' => 'Administration', 'links' => [
                        ['route' => 'admin.settings.index', 'active' => 'admin.settings.*', 'icon' => 'sliders', 'label' => 'Site Settings', 'module' => 'settings'],
                        ['route' => 'admin.users.index', 'active' => 'admin.users.*', 'icon' => 'people-fill', 'label' => 'Users', 'module' => 'users'],
                        ['route' => 'admin.activity.index', 'active' => 'admin.activity.*', 'icon' => 'clock-history', 'label' => 'Activity Log', 'module' => 'activity'],
                        ['route' => 'admin.profile.edit', 'active' => 'admin.profile.*', 'icon' => 'person-gear', 'label' => 'My Profile', 'module' => null],
                    ]],
                ])
                <nav class="mt-4">
                    @foreach ($navigation as $section)
                        {{-- Links the signed-in role cannot open are not shown at all. --}}
                        @php($visible = array_filter($section['links'], fn ($link) => $link['module'] === null || auth()->user()->can('access-module', $link['module'])))
                        @if (count($visible) > 0)
                            @if ($section['heading'])
                                <div class="sidebar-heading">{{ $section['heading'] }}</div>
                            @endif
                            @foreach ($visible as $link)
                                <a class="sidebar-link {{ request()->routeIs($link['active']) ? 'active' : '' }}" href="{{ route($link['route']) }}"><i class="bi bi-{{ $link['icon'] }}"></i>{{ $link['label'] }}</a>
                            @endforeach
                        @endif
                    @endforeach
                </nav>
            </aside>
            <div class="admin-main">
                <header class="topbar">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 px-4 py-3">
                        <div>
                            <div class="small fw-bold text-uppercase text-primary">@yield('eyebrow', 'Admin Panel')</div>
                            <h1 class="h4 fw-black mb-0">@yield('page-title', 'Dashboard')</h1>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <div class="text-end d-none d-sm-block">
                                <div class="fw-bold">{{ auth()->user()->name }}</div>
                                <div class="small text-muted">{{ str(auth()->user()->role)->headline() }}</div>
                            </div>
                            <a class="btn btn-outline-tee" href="{{ route('admin.profile.edit') }}"><i class="bi bi-person-circle me-1"></i>Profile</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="btn btn-outline-tee" type="submit"><i class="bi bi-box-arrow-right me-1"></i>Logout</button>
                            </form>
                        </div>
                    </div>
                </header>
                @isset($breadcrumbs)
                    @include('admin.partials.breadcrumbs', ['items' => $breadcrumbs])
                @endisset
                <main class="content-wrap">@yield('content')</main>
            </div>
        </div>
    @else
        @yield('content')
    @endauth
    @auth
        @include('admin.partials.confirm-delete-modal')
    @endauth
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/v/bs5/dt-3.0.4/datatables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script @nonce>
        toastr.options = { closeButton:true, progressBar:true, positionClass:'toast-top-right', timeOut:3500 };
        @if (session('success')) toastr.success(@json(session('success'))); @endif
        @if (session('error')) toastr.error(@json(session('error'))); @endif
        @if ($errors->any()) toastr.error('Please review the highlighted fields.'); @endif
        $('.select2').select2({ width: '100%' });

        function renderMediaOption(media) {
            if (!media.id) return media.text;
            const thumb = media.is_image && media.url
                ? '<img src="' + media.url + '" alt="">'
                : '<i class="bi bi-file-earmark-text"></i>';
            const meta = media.dimensions ? '<div class="small text-muted">' + media.dimensions + '</div>' : '';
            return $('<span class="d-inline-flex align-items-center gap-2"><span class="media-option-thumb">' + thumb + '</span><span>' + $('<span>').text(media.text).html() + meta + '</span></span>');
        }

        function setMediaPreview(targetId, media) {
            const frame = document.querySelector('[data-media-preview="' + targetId + '"]');
            if (!frame) return;

            if (media && media.url && media.is_image) {
                frame.innerHTML = '<img src="' + media.url + '" alt="">';
            } else if (media && media.id) {
                frame.innerHTML = '<div class="text-center text-muted small px-3"><i class="bi bi-file-earmark-text d-block fs-3 mb-1"></i>Selected file</div>';
            } else {
                frame.innerHTML = '<div class="text-center text-muted small px-3"><i class="bi bi-image d-block fs-3 mb-1"></i>No media selected</div>';
            }
        }

        $('.media-picker').each(function () {
            const $picker = $(this);

            $picker.select2({
                width: '100%',
                allowClear: true,
                placeholder: $picker.data('placeholder') || 'Search media',
                templateResult: renderMediaOption,
                ajax: {
                    url: $picker.data('options-url'),
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return { q: params.term || '', page: params.page || 1 };
                    },
                    processResults: function (data) {
                        return { results: data.results, pagination: data.pagination };
                    },
                    cache: true
                }
            });

            $picker.on('select2:select', function (event) {
                setMediaPreview($picker.data('preview-target'), event.params.data);
            });

            $picker.on('select2:clear', function () {
                setMediaPreview($picker.data('preview-target'), null);
            });
        });
        document.addEventListener('click', function (event) {
            const trigger = event.target.closest('[data-delete-action]');
            if (!trigger || trigger.hasAttribute('disabled')) return;

            const form = document.getElementById('delete-confirmation-form');
            const title = document.getElementById('delete-confirmation-title');
            const message = document.getElementById('delete-confirmation-message');

            form.action = trigger.dataset.deleteAction;
            title.textContent = trigger.dataset.deleteTitle || 'Delete this record?';
            message.textContent = trigger.dataset.deleteMessage || 'This action cannot be undone.';

            bootstrap.Modal.getOrCreateInstance(document.getElementById('delete-confirmation-modal')).show();
        });
    </script>
    @stack('scripts')
</body>
</html>
