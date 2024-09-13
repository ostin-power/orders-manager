<?php

namespace App\Interfaces;

interface ProductRepositoryInterface {
    public function index();
    public function store(string $name, int $price);
}
