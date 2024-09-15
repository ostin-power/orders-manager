<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Orders</title>

        <!-- Bootstrap -->
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

        <!-- Icons -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

        <!-- JQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!-- Alerts -->
        <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- CSS -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    </head>
    <body class="d-flex flex-column min-vh-100">
        <!-- header -->
        @include('layouts.header')

        <!-- app content -->
        <main role="main" class="container mt-5 flex-grow-1">
            @yield('content')
        </main>

        <!-- footer -->
        @include('layouts.footer')

        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


        <!-- Javascript -->
        <script src="{{ asset('js/app.js') }}"></script>
        <script src="{{ asset('js/order.js') }}"></script>
        <script src="{{ asset('js/product.js') }}"></script>

    </body>
</html>
