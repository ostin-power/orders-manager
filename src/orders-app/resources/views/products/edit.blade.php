<h5 class="main-color">ID: {{ $product['id'] }}</h5>
<form id="updateProductForm" action="{{ route('products.update', $product['id']) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label for="name">Name</label>
        <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $product['name']) }}" disabled>
    </div>
    <div class="form-group">
        <label for="price">Price</label>
        <textarea id="price" name="price" class="form-control" rows="3" required>{{ old('price', $product['price']) }}</textarea>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-main">Update</button>
    </div>
</form>

<script>
    $(document).ready(function() {
        $('#updateProductForm').on('submit', function(e) {
            e.preventDefault();

            // Serialize form data
            var formData = $(this).serialize();

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                success: function(response) {
                    $('#productModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Product Updated',
                        text: 'The product was successfully updated!',
                        showConfirmButton: true
                    }).then(() => {
                        location.reload();
                    });
                },
                error: function(xhr) {
                    var response = xhr.responseJSON || {};
                    var errorMessage = response.message || 'Failed to update product. Please try again.';

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
    });
</script>
