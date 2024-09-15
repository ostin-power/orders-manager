$(document).ready(function() {
    // Show Order Modal
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

    // Edit Order Modal
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

    // Insert Modal
    $('#insertOrderForm').on('submit', function(e) {
        e.preventDefault();
        var formData = $(this).serialize();

        // Check if at least one product quantity is greater than zero
        var hasQuantity = false;
        $('input[name^="products"][name$="[quantity]"]').each(function() {
            var quantity = $(this).val();
            if (quantity && parseInt(quantity) > 0) {
                hasQuantity = true;
                return false;
            }
        });

        // If no valid quantity is set, show an alert and stop form submission
        if (!hasQuantity) {
            Swal.fire({
                icon: 'warning',
                title: 'Invalid Quantity',
                text: 'Please set a quantity for at least one product.',
            });
            return;
        }

        $.ajax({
            url: "/order/store",
            type: 'POST',
            data: formData,
            success: function(response) {
                $('#insertOrderModal').modal('hide');
                $('#insertOrderForm')[0].reset();

                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Order inserted successfully!',
                }).then(() => {
                    location.reload();
                });
            },
            error: function(xhr) {
                // Check if the response is JSON
                var response = xhr.responseJSON || {};
                var errorMessage = response.message || 'Failed to insert order. Please try again.';

                Swal.fire({
                    icon: 'error',
                    title: 'Failed',
                    text: errorMessage,
                }).then(() => {
                    location.reload();
                });
            }
        });
    });

    //Delete Order
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
