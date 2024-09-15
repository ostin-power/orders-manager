<?php

namespace App\Interfaces;

interface ProductRepositoryInterface {
    public function index();
    public function show(int $id);
    public function store(string $name, int $price);
    public function update(int $id, int $price);
    public function delete(int $id);
}
