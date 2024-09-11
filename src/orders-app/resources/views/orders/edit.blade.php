<h1 class="main-color">Edit Order #{{ $order->id }}</h1>

<form action="{{ route('orders.update', $order->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label for="name">Name</label>
        <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $order->name) }}" required>
    </div>
    <div class="form-group">
        <label for="description">Description</label>
        <textarea id="description" name="description" class="form-control" rows="3" required>{{ old('description', $order->description) }}</textarea>
    </div>
    <div class="form-group">
        <label for="date">Date</label>
        <input type="date" id="date" name="date" class="form-control" value="{{ old('date', $order->date) }}" required>
    </div>
    <button type="submit" class="btn btn-main">Update Order</button>
</form>
