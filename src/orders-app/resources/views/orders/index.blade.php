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
                <button type="submit" class="btn btn-main"><i class="fa-solid fa-filter"></i> Filter</button>
                <a href="{{ route('orders.index') }}" class="btn btn-secondary"><i class="fa-solid fa-filter-circle-xmark"></i> Clear</a>
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#insertOrderModal"><i class="fa-solid fa-plus"></i> Insert</button>
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
                        <div class="d-flex align-items-center justify-content-between">
                            <button class="btn btn-info btn-sm show-modal" data-id="{{ $order->id }}" data-url="{{ route('orders.show', $order->id) }}"><i class="fa-solid fa-circle-info"></i> View</button>
                            <button class="btn btn-secondary btn-sm edit-modal" data-id="{{ $order->id }}" data-url="{{ route('orders.edit', $order->id) }}"><i class="fa-regular fa-pen-to-square"></i> Edit</button>
                            <form action="{{ route('orders.delete', $order->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')"><i class="fa-solid fa-trash"></i> Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @include('components.show-edit-order-modal')
    @include('components.insert-order-modal', ['products' => $products])

@endsection

@section('scripts')
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
                        console.error('Error fetching show data:', error);
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
                        console.error('Error fetching edit data:', error); // Log any errors
                    }
                });
            });

            // Insert Modal
            $('#insertOrderForm').on('submit', function(e) {
                e.preventDefault();
                var formData = $(this).serialize();

                $.ajax({
                    url: "{{ route('orders.store') }}",
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        $('#insertOrderModal').modal('hide');
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
