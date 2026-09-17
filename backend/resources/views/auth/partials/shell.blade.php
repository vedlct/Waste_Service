<style>
    .login-page { min-height:100vh; display:grid; place-items:center; padding:1.5rem; background:radial-gradient(circle at 18% 18%, rgba(4,151,226,.22), transparent 28rem), radial-gradient(circle at 82% 72%, rgba(244,185,66,.22), transparent 25rem), linear-gradient(135deg, #eef7fd 0%, #fff 45%, #f7fbff 100%); }
    .login-shell { width:min(100%, 70rem); display:grid; grid-template-columns:minmax(0,.95fr) minmax(22rem,.7fr); overflow:hidden; border-radius:1.75rem; background:rgba(255,255,255,.82); border:1px solid rgba(17,34,77,.1); box-shadow:0 40px 100px rgba(17,34,77,.16); backdrop-filter:blur(18px); }
    .login-showcase { position:relative; min-height:38rem; padding:2rem; color:#fff; background:linear-gradient(0deg, rgba(17,34,77,.76), rgba(17,34,77,.18)), url("{{ asset('images/HeroImage.jpg') }}") center/cover; }
    .login-showcase::after { content:""; position:absolute; inset:auto 2rem 2rem 2rem; height:1px; background:rgba(255,255,255,.22); }
    .login-copy { position:absolute; left:2rem; right:2rem; bottom:3.25rem; }
    .login-card { padding:clamp(1.5rem, 4vw, 3rem); display:flex; flex-direction:column; justify-content:center; }
    .login-logo { max-height:58px; width:auto; }
    .form-floating > .form-control { border-radius:1rem; min-height:3.6rem; border-color:#dbe8f2; }
    .login-button { min-height:3.2rem; border-radius:1rem; background:#11224d; border-color:#11224d; font-weight:900; }
    .login-button:hover { background:#0497e2; border-color:#0497e2; }
    @media (max-width:900px) { .login-shell { grid-template-columns:1fr; } .login-showcase { min-height:18rem; } }
</style>

<main class="login-page">
    <section class="login-shell">
        <div class="login-showcase">
            <img src="{{ asset('images/MainLogo.png') }}" alt="MR. TEE" style="max-height:52px;width:auto;">
            <div class="login-copy">
                <p class="small fw-bold text-uppercase text-warning mb-2">{{ $showcaseEyebrow ?? 'Content operations' }}</p>
                <h1 class="display-6 fw-black mb-3">{{ $showcaseTitle ?? 'Manage the site with a cleaner, faster workspace.' }}</h1>
                <p class="mb-0 text-white-50">{{ $showcaseDescription ?? 'Services, prices, bookings and customer content will live here as the admin panel grows.' }}</p>
            </div>
        </div>

        <div class="login-card">
            <div class="mb-4">
                <img class="login-logo mb-4" src="{{ asset('images/MainLogo.png') }}" alt="MR. TEE">
                <p class="small fw-bold text-uppercase text-primary mb-2">{{ $eyebrow }}</p>
                <h2 class="fw-black mb-2">{{ $title }}</h2>
                <p class="text-muted mb-0">{{ $description }}</p>
            </div>

            {{ $slot }}
        </div>
    </section>
</main>
