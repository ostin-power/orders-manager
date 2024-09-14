@extends('layouts.app')

@section('content')
    <h3 class="main-color">Products List</h1>

    <div class="row" style="padding-bottom: 15px;">
        <div class="col-md-3"></div>
        <div class="col-md-3"></div>
        <div class="col-md-3"></div>
        <div class="col-md-3 d-flex justify-content-end">
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#insertProductModal">
                <i class="fa-solid fa-plus"></i> Insert
            </button>
        </div>
    </div>

    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th style="width: 3%;">ID</th>
                <th style="width: 69%;">Name</th>
                <th style="width: 10%;">Price</th>
                <th style="width: 15%;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
                <tr>
                    <td style="width: 3%;">{{ $product['id'] }}</td>
                    <td style="width: 69%;">{{ $product['name'] }}</td>
                    <td style="width: 10%;">{{ $product['price'] }}</td>
                    <td style="width: 15%;">
                        <div class="d-flex align-items-center justify-content-between">
                            <button class="btn btn-secondary btn-sm edit-product-modal mr-2" data-id="{{ $product['id'] }}" data-url="{{ route('products.show', $product['id']) }}"><i class="fa-regular fa-pen-to-square"></i> Edit</button>
                            <form id="deleteProductForm" action="{{ route('products.delete', $product['id']) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger btn-sm delete-btn-product"><i class="fa-solid fa-trash"></i> Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @include('components.edit-product-modal')
    @include('components.insert-product-modal')

@endsection
