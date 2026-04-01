<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Course Module' }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 2rem; }
        .container { max-width: 1100px; margin: 0 auto; }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        th, td { border: 1px solid #ddd; padding: 0.6rem; text-align: left; }
        .badge { display: inline-block; padding: 0.2rem 0.6rem; border-radius: 4px; color: #fff; font-size: 0.85rem; }
        .badge-secondary { background: #6c757d; }
        .badge-success { background: #198754; }
        .badge-dark { background: #212529; }
        .badge-primary { background: #0d6efd; }
        .badge-warning { background: #ffc107; color: #000; }
        .btn { display: inline-block; padding: 0.4rem 0.8rem; background: #0d6efd; color: #fff; text-decoration: none; border: 0; border-radius: 4px; cursor: pointer; }
        .btn-danger { background: #dc3545; }
        .btn-secondary { background: #6c757d; }
        .row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        .mb-3 { margin-bottom: 1rem; }
    </style>
</head>
<body>
<div class="container">
    @if (session('success'))
        <p style="color: #198754;">{{ session('success') }}</p>
    @endif

    @if ($errors->any())
        <div style="color: #dc3545;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('content')
</div>
</body>
</html>
