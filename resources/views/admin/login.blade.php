<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <title>Sign in | DevKei Admin</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;500;600;700&family=Young+Serif&display=swap">
    <link rel="stylesheet" href="{{ asset('css/devkei.css') }}?v=2">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}?v=2">
</head>
<body class="admin">
<div class="login-split">
    <aside class="login-art">
        <a class="side-brand" href="{{ route('home') }}">@include('partials.logo')<span>DevKei <em>Admin</em></span></a>
        <div>
            <h1>Run the website from one place.</h1>
            <p>Products, demo and quote requests, and where your visitors come from.</p>
        </div>
    </aside>
    <main class="login-form">
        <form class="login-card" method="POST" action="{{ route('admin.login.attempt') }}">
            @csrf
            <h2>Sign in</h2>
            <p class="muted">Use your admin account.</p>
            <div class="field @error('email') has-error @enderror">
                <label for="email">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="username" required autofocus>
                @error('email')<p class="error">{{ $message }}</p>@enderror
            </div>
            <div class="field">
                <label for="password">Password</label>
                <input id="password" name="password" type="password" autocomplete="current-password" required>
            </div>
            <label class="switch"><input type="checkbox" name="remember" value="1"> <span>Keep me signed in</span></label>
            <button class="btn btn-primary btn-block" type="submit">Sign in</button>
        </form>
    </main>
</div>
</body>
</html>
