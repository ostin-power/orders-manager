<h5 class="main-color">ID: {{ $order['id'] }}</h5>
<form id="updateOrderForm" action="{{ route('orders.update', $order['id']) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label for="name">Name</label>
        <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $order['name']) }}" required>
    </div>
    <div class="form-group">
        <label for="description">Description</label>
        <textarea id="description" name="description" class="form-control" rows="3" required>{{ old('description', $order['description']) }}</textarea>
    </div>
    <div class="form-group">
        <label for="date">Date</label>
        <input type="date" id="date" name="date" class="form-control" value="{{ old('date', date('Y-m-d', strtotime($order['date'])) ) }}" required>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-main">Update Order</button>
    </div>
</form>

<script>
    $(document).ready(function() {
        $('#updateOrderForm').on('submit', function(e) {
            e.preventDefault();

            // Serialize form data
            var formData = $(this).serialize();

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                success: function(response) {
                    $('#orderModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Order Updated',
                        text: 'The order was successfully updated!',
                        showConfirmButton: true
                    }).then(() => {
                        location.reload();
                    });
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to update the order. Please try again.',
                    });
                }
            });
        });
    });
</script>
