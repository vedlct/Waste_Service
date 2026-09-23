<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') | {{ config('app.name', 'MR. TEE') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { color:#11224d; font-size:14px; background:#fff; }
        .sheet { max-width:980px; margin:0 auto; padding:1.5rem; }
        .job { border:1px solid #cfd9e3; border-radius:.75rem; padding:1rem; margin-bottom:1rem; break-inside:avoid; page-break-inside:avoid; }
        .job h2 { font-size:1.1rem; font-weight:900; margin:0; }
        .label { font-size:.7rem; font-weight:800; letter-spacing:.08em; text-transform:uppercase; color:#6c7280; }
        .warning { border-left:4px solid #f4b942; background:#fffbef; padding:.4rem .6rem; }
        @media print {
            .no-print { display:none !important; }
            .sheet { padding:0; max-width:none; }
            a { color:inherit; text-decoration:none; }
        }
    </style>
</head>
<body>
    <main class="sheet">@yield('content')</main>
    @stack('scripts')
</body>
</html>
