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
