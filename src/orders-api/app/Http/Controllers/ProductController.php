<?php

namespace App\Http\Controllers;

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

    /**
     * @OA\Get(
     *     path="/api/v1/products",
     *     summary="Retrieve a list of products",
     *     tags={"Products"},
     *     @OA\Response(
     *         response="200",
     *         description="Successful response",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="products", type="array", @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=31),
     *                 @OA\Property(property="name", type="string", example="alias"),
     *                 @OA\Property(property="price", type="string", example="55.54"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-09-11T07:50:15.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-09-11T07:50:15.000000Z")
     *             ))
     *         )
     *     )
     * )
     */
    public function index() {
        return response()->json([
            'code'      => 200,
            'products'  => $this->_productRepository->index()
        ]);
    }
}
