@extends('layouts.app')

@section('content')
    <h3 class="main-color">Order List</h1>

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

    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th style="width: 3%;">ID</th>
                <th style="width: 15%;">Name</th>
                <th style="width: 47%;">Description</th>
                <th style="width: 12%;">Date</th>
                <th style="width: 23%;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
                <tr>
                    <td style="width: 3%;">{{ $order['id'] }}</td>
                    <td style="width: 15%;">{{ $order['name'] }}</td>
                    <td style="width: 47%;">{{ $order['description'] }}</td>
                    <td style="width: 12%;">{{ date('d-m-Y', strtotime($order['date']))  }}</td>
                    <td style="width: 23%;">
                        <div class="d-flex align-items-center justify-content-between">
                            <button class="btn btn-info btn-sm show-modal mr-2" data-id="{{ $order['id'] }}" data-url="{{ route('orders.show', $order['id']) }}"><i class="fa-solid fa-circle-info"></i> View</button>
                            <button class="btn btn-secondary btn-sm edit-modal mr-2" data-id="{{ $order['id'] }}" data-url="{{ route('orders.edit', $order['id']) }}"><i class="fa-regular fa-pen-to-square"></i> Edit</button>
                            <form id="deleteOrderForm" action="{{ route('orders.delete', $order['id']) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger btn-sm delete-btn"><i class="fa-solid fa-trash"></i> Delete</button>
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
