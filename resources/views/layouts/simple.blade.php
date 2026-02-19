<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('judul', 'Klinik Rawat Inap')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-green-50 via-white to-green-100">
    @yield('konten')
</body>
</html>
