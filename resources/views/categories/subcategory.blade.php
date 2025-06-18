<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subcategory->name }} - Subcategory</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-50">
    @include('navigation_bars.unreg_user_nav')

    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold">{{ $subcategory->name }}</h1>
        <p class="mt-2">Details about the {{ $subcategory->name }} subcategory go here.</p>
        <!-- Add additional subcategory details or list related properties -->
    </div>

    @include('footer.footer')
</body>
</html>
