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
                <th>ID</th>
                <th>Name</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
                <tr>
                    <td>{{ $product['id'] }}</td>
                    <td>{{ $product['name'] }}</td>
                    <td>{{ $product['price'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @include('components.insert-product-modal')

@endsection
