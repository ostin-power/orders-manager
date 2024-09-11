<div class="modal fade" id="insertOrderModal" tabindex="-1" role="dialog" aria-labelledby="insertOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
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
                        <div id="products-container">
                            @foreach($products as $product)
                                <div class="form-row product-row alig-center">
                                    <div class="col-md-8">
                                        <label for="product-{{ $product->id }}">{{ $product->name }}</label>
                                        <input type="hidden" name="products[{{ $product->id }}][id]" value="{{ $product->id }}">
                                        <input type="number" id="quantity-{{ $product->id }}" name="products[{{ $product->id }}][quantity]" class="form-control" placeholder="Quantity" min="1">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <button type="submit" class="btn btn-main">Insert Order</button>
                </form>
            </div>
        </div>
    </div>
</div>
