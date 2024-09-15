$(document).ready(function() {
    // Insert Product Modal
    $('#insertProductForm').on('submit', function(e) {
        e.preventDefault();
        var formData = $(this).serialize();

        $.ajax({
            url: "/product/store",
            type: 'POST',
            data: formData,
            success: function(response) {
                $('#insertProductModal').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Product inserted successfully!',
                }).then(() => {
                    location.reload(); // refresh to see the new product
                });
            },
            error: function(xhr) {
                // Check if the response is JSON
                var response = xhr.responseJSON || {};
                var errorMessage = response.message || 'Failed to insert product. Please try again.';

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

    // Edit Product Modal
    $(document).on('click', '.edit-product-modal', function() {
        var url = $(this).data('url');

        $.ajax({
            url: url,
            type: 'GET',
            success: function(response) {
                $('#productModalLabel').text('Edit Product');
                $('#productModalContent').html(response);
                $('#productModal').modal('show');
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Error fetching data!',
                });
                console.error('Error fetching edit data:', error); // Log errors
            }
        });
    });

    //Delete Product
    $('.delete-btn-product').on('click', function(e) {
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
                        location.reload();
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: 'The product has been deleted.',
                        });
                    },
                    error: function(xhr) {
                        var response = xhr.responseJSON || {};
                        var errorMessage = response.message || 'Failed to delete product. Please try again.';

                        Swal.fire({
                            icon: 'error',
                            title: 'Failed',
                            text: errorMessage,
                        });

                        console.error('Error deleting product:', xhr);
                    }
                });
            }
        });
    });
});
