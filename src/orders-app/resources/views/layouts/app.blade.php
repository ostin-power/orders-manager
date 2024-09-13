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
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Error fetching data!',
                            });
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
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Error fetching data!',
                            });
                            console.error('Error fetching edit data:', error); //log errors
                        }
                    });
                });

                $('.delete-btn').on('click', function(e) {
                    e.preventDefault();
                    var form = $(this).closest('form');

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: form.attr('action'),
                                type: 'POST',
                                data: form.serialize(),
                                success: function(response) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Deleted!',
                                        text: 'The order has been deleted.',
                                    }).then(() => {
                                        location.reload(); // Reload the page to reflect changes
                                    });
                                },
                                error: function(xhr) {
                                    var response = xhr.responseJSON || {};
                                    var errorMessage = response.message || 'Failed to delete order. Please try again.';

                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Failed',
                                        text: errorMessage,
                                    });

                                    console.error('Error deleting order:', xhr);
                                }
                            });
                        }
                    });
                });
            });
        </script>
    </body>
</html>
