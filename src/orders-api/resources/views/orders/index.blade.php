@extends('layouts.app')

@section('content')
    <h1 class="main-color">Order List</h1>

    <form method="GET" action="{{ route('orders.index') }}" class="mb-4">
        <div class="form-row">
            <div class="col-md-3">
                <input type="text" name="name" class="form-control" placeholder="Search by name" value="{{ request('name') }}">
            </div>
            <div class="col-md-3">
                <input type="text" name="description" class="form-control" placeholder="Search by description" value="{{ request('description') }}">
            </div>
            <div class="col-md-3">
                <input type="date" name="date" class="form-control" value="{{ request('date') }}">
            </div>
            <div class="col-md-3 d-flex align-items-center justify-content-between">
                <button type="submit" class="btn btn-main">Filter</button>
                <a href="{{ route('orders.index') }}" class="btn btn-secondary">Clear</a>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#insertOrderModal">Insert</button>
            </div>
        </div>
    </form>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->name }}</td>
                    <td>{{ $order->description }}</td>
                    <td>{{ $order->date }}</td>
                    <td>
                        <button class="btn btn-info btn-sm show-modal" data-id="{{ $order->id }}" data-url="{{ route('orders.show', $order->id) }}">View</button>
                        <button class="btn btn-warning btn-sm edit-modal" data-id="{{ $order->id }}" data-url="{{ route('orders.edit', $order->id) }}">Edit</button>
                        <form action="{{ route('orders.delete', $order->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="modal fade" id="orderModal" tabindex="-1" role="dialog" aria-labelledby="orderModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderModalLabel">Order Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="orderModalContent">
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="insertOrderModal" tabindex="-1" role="dialog" aria-labelledby="insertOrderModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
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
                                    <div class="form-row product-row">
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

@endsection

@section('scripts')
    <script>
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
                        console.error('Error fetching show data:', error);
                    }
                });
            });

            // Edit Modal
            $(document).on('click', '.edit-modal', function() {
                var url = $(this).data('url');
                console.log('Fetching URL for edit:', url);

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        $('#orderModalLabel').text('Edit Order');
                        $('#orderModalContent').html(response);
                        $('#orderModal').modal('show');
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching edit data:', error); // Log any errors
                    }
                });
            });

            // Insert Modal
            $('#insertOrderForm').on('submit', function(e) {
                e.preventDefault(); // Prevent the default form submission

                var formData = $(this).serialize(); // Serialize form data

                $.ajax({
                    url: "{{ route('orders.store') }}", // Adjust to your store route
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        // Close the modal
                        $('#insertOrderModal').modal('hide');

                        // Clear the form
                        $('#insertOrderForm')[0].reset();

                        // Optionally, refresh the order list or append the new order to the table
                        alert('Order inserted successfully!');
                        location.reload(); // Optionally refresh the page to see the new order
                    },
                    error: function(xhr, status, error) {
                        console.error('Error inserting order:', error);
                        alert('Failed to insert order. Please try again.');
                    }
                });
            });
        });
    </script>
@endsection
