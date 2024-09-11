<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\ProductRepositoryInterface;

class ProductController extends Controller {

    /**
     * Product repository interface
     */
    private $_productRepository;

    /**
     * Class constructor
     *
     * @return void
     */
    public function __construct(ProductRepositoryInterface $productRepositoryInterface) {
        $this->_productRepository = $productRepositoryInterface;
    }

}
