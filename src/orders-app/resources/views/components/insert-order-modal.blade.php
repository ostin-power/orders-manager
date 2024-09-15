<div class="modal fade" id="insertOrderModal" tabindex="-1" role="dialog" aria-labelledby="insertOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #C82333; color: white;">
                <h5 class="modal-title" id="insertOrderModalLabel">Insert New Order</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="insertOrderForm" method="POST" action="{{ route('orders.store') }}">
                    @csrf
                    <div class="form-group">
                        <label for="name">Order Name</label>
                        <input type="text" id="name" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Order Description</label>
                        <textarea id="description" name="description" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="date">Date</label>
                        <input type="date" id="date" name="date" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="products">Select Products</label>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 60%;">Product Name</th>
                                    <th style="width: 40%;">Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                    <tr>
                                        <td style="width: 70%;">
                                            <label for="product-{{ $product['id'] }}">{{ $product['name'] }}</label>
                                            <input type="hidden" name="products[{{ $product['id'] }}][id]" value="{{ $product['id'] }}">
                                        </td>
                                        <td style="width: 30%;">
                                            <input type="number" id="quantity-{{ $product['id'] }}" name="products[{{ $product['id'] }}][quantity]" class="form-control" placeholder="Quantity" min="1">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-main">Insert Order</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
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
                url: "{{ route('orders.store') }}",
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
    });
</script>
