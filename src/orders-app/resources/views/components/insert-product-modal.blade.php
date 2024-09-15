<div class="modal fade" id="insertProductModal" tabindex="-1" role="dialog" aria-labelledby="insertProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #C82333; color: white;">
                <h5 class="modal-title" id="insertProductModalLabel">Insert New Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="insertProductForm" method="POST" action="{{ route('products.store') }}">
                    @csrf
                    <div class="form-group d-flex align-items-center">
                        <label for="name" style="width: 20%;">Product Name</label>
                        <div style="width: 80%;">
                            <input type="text" id="name" name="name" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group d-flex align-items-center">
                        <label for="price" style="width: 20%;">Price</label>
                        <div style="width: 80%;">
                            <input id="price" name="price" class="form-control" required></input>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-main">Insert Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
