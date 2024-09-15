<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface OrderRepositoryInterface {

    public function index(Request $request);
    public function show (int $order_id);
    public function store(string $name, string $description, $date, array $products);
    public function update(int $order_id, string $name, string $description, $date, array $products);
    public function delete(int $order_id);
}
