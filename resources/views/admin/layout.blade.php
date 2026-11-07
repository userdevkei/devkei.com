@php
    $navDemos = \App\Models\DemoRequest::where('status', 'new')->count();
    $navQuotes = \App\Models\QuoteRequest::where('status', 'new')->count();
    $adminName = auth()->user()->name ?? 'Admin';
    $initials = collect(explode(' ', trim($adminName)))->filter()->take(2)->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))->implode('') ?: 'A';
    $flash = array_filter([
        'success' => session('success'),
        'error' => $errors->any() && ! $errors->has('email') ? 'Some fields need fixing. Check the form.' : null,
    ]);
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') | DevKei Admin</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;500;600;700&family=Young+Serif&display=swap">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="{{ asset('css/devkei.css') }}?v=2">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}?v=2">
    @stack('head')
</head>
<body class="admin">
<div class="admin-shell">
    <aside class="admin-side" id="admin-side">
        <a class="side-brand" href="{{ route('admin.dashboard') }}">@include('partials.logo')<span>DevKei <em>Admin</em></span></a>

        <nav class="side-nav" aria-label="Admin">
            <a href="{{ route('admin.dashboard') }}" @if(request()->routeIs('admin.dashboard')) aria-current="page" @endif><x-admin.icon name="home"/> Dashboard</a>

            <p class="side-group">Analytics</p>
            <a href="{{ route('admin.analytics.index') }}" @if(request()->routeIs('admin.analytics.index')) aria-current="page" @endif><x-admin.icon name="chart"/> Traffic</a>
            <a href="{{ route('admin.analytics.visitors') }}" @if(request()->routeIs('admin.analytics.visitors')) aria-current="page" @endif><x-admin.icon name="list"/> Visitor log</a>

            <p class="side-group">Leads</p>
            <a href="{{ route('admin.leads.index', 'demos') }}" @if(request()->is('admin/leads/demos*')) aria-current="page" @endif><x-admin.icon name="calendar"/> Demo requests @if($navDemos)<span class="count">{{ $navDemos }}</span>@endif</a>
            <a href="{{ route('admin.leads.index', 'quotes') }}" @if(request()->is('admin/leads/quotes*')) aria-current="page" @endif><x-admin.icon name="file"/> Quote requests @if($navQuotes)<span class="count">{{ $navQuotes }}</span>@endif</a>

            <p class="side-group">Content</p>
            <a href="{{ route('admin.products.index') }}" @if(request()->routeIs('admin.products.*')) aria-current="page" @endif><x-admin.icon name="box"/> Products</a>
        </nav>

        <a class="side-site" href="{{ route('home') }}" target="_blank" rel="noopener"><x-admin.icon name="external" :size="16"/> View website</a>
    </aside>
    <div class="side-backdrop" data-side-close hidden></div>

    <div class="admin-body">
        <header class="admin-topbar">
            <button class="icon-btn menu-btn" type="button" data-side-toggle aria-label="Open menu"><x-admin.icon name="menu"/></button>
            <div class="topbar-spacer"></div>
            <span class="live-chip" data-live-url="{{ route('admin.analytics.live') }}" title="Visitors in the last 5 minutes"><i></i><b data-live-count>0</b> online</span>
            <details class="user-menu">
                <summary aria-label="Account menu"><span class="avatar">{{ $initials }}</span><span class="user-name">{{ $adminName }}</span></summary>
                <div class="user-pop">
                    <form method="POST" action="{{ route('admin.logout') }}">@csrf
                        <button type="submit"><x-admin.icon name="logout" :size="16"/> Sign out</button>
                    </form>
                </div>
            </details>
        </header>

        <main class="admin-main">
            <div class="page-head-admin">
                <div>
                    <h3>@yield('heading')</h3>
                    @hasSection('subtitle')<p>@yield('subtitle')</p>@endif
                </div>
                <div class="page-actions">@yield('actions')</div>
            </div>

            @yield('content')
        </main>
    </div>
</div>

<div id="toast-root" data-config="{{ json_encode($flash) }}" aria-live="polite"></div>

<dialog id="confirm-dialog" class="dialog">
    <form method="dialog">
        <h3 id="confirm-title">Are you sure?</h3>
        <p id="confirm-text"></p>
        <div class="dialog-actions">
            <button class="btn btn-outline" value="cancel">Cancel</button>
            <button class="btn btn-danger-solid" value="ok">Delete</button>
        </div>
    </form>
</dialog>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
@stack('vendor')
<script src="{{ asset('js/admin.js') }}?v=2"></script>
@stack('scripts')

<script>
    (function () {
        var KEY = 'devkei.sidebar';
        var mq = window.matchMedia('(max-width: 960px)');
        var body = document.body;

        function side() { return document.querySelector('.admin-side'); }
        function backdrop() {
            var b = document.querySelector('.side-backdrop');
            if (!b) {
                b = document.createElement('div');
                b.className = 'side-backdrop';
                b.hidden = true;
                document.body.appendChild(b);
            }
            return b;
        }
        function closeMobile() {
            var s = side();
            if (s) s.classList.remove('is-open');
            backdrop().hidden = true;
        }

        try {
            if (!mq.matches && localStorage.getItem(KEY) === '1') body.classList.add('sidebar-collapsed');
        } catch (e) {}

        document.addEventListener('click', function (e) {
            if (e.target.closest('.menu-btn')) {
                e.preventDefault();
                var s = side();
                if (!s) return;
                if (mq.matches) {
                    var open = s.classList.toggle('is-open');
                    backdrop().hidden = !open;
                } else {
                    var c = body.classList.toggle('sidebar-collapsed');
                    try { localStorage.setItem(KEY, c ? '1' : '0'); } catch (e2) {}
                }
                return;
            }
            if (e.target.closest('.side-backdrop') || (mq.matches && e.target.closest('.admin-side a'))) closeMobile();
        });

        document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeMobile(); });
        if (mq.addEventListener) mq.addEventListener('change', closeMobile); else mq.addListener(closeMobile);
    })();
</script>
</body>
</html>
