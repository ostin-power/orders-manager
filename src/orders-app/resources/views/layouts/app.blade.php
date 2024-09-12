<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Orders</title>

        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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

        <!-- In laravel 11 JS must be compiled -->
        <script>
            function showEditMode() {
                document.getElementById('editFooter').classList.remove('d-none');
            }

            function hideEditMode() {
                document.getElementById('editFooter').classList.add('d-none');
            }

            $(document).ready(function() {
                // Show Modal
                $(document).on('click', '.show-modal', function() {
                    var url = $(this).data('url');

                    $.ajax({
                        url: url,
                        type: 'GET',
                        success: function(response) {
                            $('#orderModalLabel').text('Order Details');
                            $('#orderModalContent').html(response);
                            $('#orderModal').modal('show');
                        },
                        error: function(xhr, status, error) {
                            alert('Error fetching data!');
                            console.error('Error fetching show data:', error); //log errors
                        }
                    });
                });

                // Edit Modal
                $(document).on('click', '.edit-modal', function() {
                    var url = $(this).data('url');

                    $.ajax({
                        url: url,
                        type: 'GET',
                        success: function(response) {
                            $('#orderModalLabel').text('Edit Order');
                            $('#orderModalContent').html(response);
                            $('#orderModal').modal('show');
                        },
                        error: function(xhr, status, error) {
                            alert('Error fetching data!');
                            console.error('Error fetching edit data:', error); //log errors
                        }
                    });
                });

                // Insert Modal
                $('#insertOrderForm').on('submit', function(e) {
                    e.preventDefault();
                    var formData = $(this).serialize();

                    $.ajax({
                        url: "{{ route('orders.store') }}",
                        type: 'POST',
                        data: formData,
                        success: function(response) {
                            $('#insertOrderModal').modal('hide');
                            $('#insertOrderForm')[0].reset();

                            // Optionally, refresh the order list or append the new order to the table
                            alert('Order inserted successfully!');
                            location.reload(); // refresh to see the new order
                        },
                        error: function(xhr, status, error) {
                            console.error('Error inserting order:', error);
                            alert('Failed to insert order. Please try again.');
                        }
                    });
                });
            });
        </script>
    </body>
</html>
