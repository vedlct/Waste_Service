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
        .admin-sidebar { background:#0d1b3f; color:#fff; position:sticky; top:0; height:100vh; padding:1.25rem; }
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
                <nav class="mt-4">
                    <a class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}"><i class="bi bi-grid-1x2-fill"></i>Dashboard</a>
                    <a class="sidebar-link {{ request()->routeIs('admin.media.*') ? 'active' : '' }}" href="{{ route('admin.media.index') }}"><i class="bi bi-images"></i>Media</a>
                    <a class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}"><i class="bi bi-people-fill"></i>Users</a>
                    <a class="sidebar-link {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}" href="{{ route('admin.profile.edit') }}"><i class="bi bi-person-gear"></i>My Profile</a>
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
    <script>
        toastr.options = { closeButton:true, progressBar:true, positionClass:'toast-top-right', timeOut:3500 };
        @if (session('success')) toastr.success(@json(session('success'))); @endif
        @if (session('error')) toastr.error(@json(session('error'))); @endif
        @if ($errors->any()) toastr.error('Please review the highlighted fields.'); @endif
        $('.select2').select2({ width: '100%' });
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
