<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
</head>
<body style="margin:0;min-height:100vh;display:grid;place-items:center;background:#f5f5f5;color:#1f2937;font-family:Arial,sans-serif;">
    <script>
        window.location.href = "{{ route('home') }}";
    </script>
    <div style="text-align:center;">
        <p>Redirecting to the app...</p>
        <a href="{{ route('home') }}" style="color:#0d6efd; text-decoration:none;">Go to homepage</a>
    </div>
</body>
</html>
