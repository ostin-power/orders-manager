<h5 class="main-color">ID: {{ $order['id'] }}</h5>

<table class="table table-striped table-bordered">
    <tbody>
        <tr>
            <td><strong>Name:</strong></td>
            <td>{{ $order['name'] }}</td>
        </tr>
        <tr>
            <td><strong>Description:</strong></td>
            <td>{{ $order['description'] }}</td>
        </tr>
        <tr>
            <td><strong>Date:</strong></td>
            <td>{{ $order['date'] }}</td>
        </tr>
    </tbody>
</table>

<h4>Products</h4>
<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>Name</th>
            <th>Quantity</th>
            <th>Price</th>
        </tr>
    </thead>
    <tbody>
        @foreach($order['products'] as $product)
            <tr>
                <td>{{ $product['name'] }}</td>
                <td>{{ $product['pivot']['quantity'] }}</td>
                <td>{{ $product['price'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

