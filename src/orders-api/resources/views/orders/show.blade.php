@extends('layouts.app')

@section('content')
    <h1 class="main-color">Order #{{ $order->id }}</h1>

    <h3>Details</h3>
    <p><strong>Name:</strong> {{ $order->name }}</p>
    <p><strong>Description:</strong> {{ $order->description }}</p>
    <p><strong>Date:</strong> {{ $order->date }}</p>

    <h3>Products</h3>
    <ul>
        @foreach($order->products as $product)
            <li>{{ $product->name }} - {{ $product->pivot->quantity }}</li>
        @endforeach
    </ul>

    <a href="{{ route('orders.index') }}" class="btn btn-secondary">Back to List</a>
@endsection
