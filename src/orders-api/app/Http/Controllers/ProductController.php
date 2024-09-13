<?php

namespace App\Http\Controllers;

use App\Interfaces\ProductRepositoryInterface;
use Illuminate\Http\Request;

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

    /**
     * @OA\Post(
     *     path="/products",
     *     summary="Create a new product",
     *     tags={"Products"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "price"},
     *             @OA\Property(property="name", type="string", example="Product Name", description="Name of the product"),
     *             @OA\Property(property="price", type="integer", example=20, description="Price of the product")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Product created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=201),
     *             @OA\Property(
     *                 property="product_created",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=6),
     *                 @OA\Property(property="name", type="string", example="Product Name"),
     *                 @OA\Property(property="price", type="integer", example=20),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-09-13T14:31:19.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-09-13T14:31:19.000000Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Product creation failed",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=500),
     *             @OA\Property(property="message", type="string", example="Product creation failed")
     *         )
     *     )
     * )
     */
    public function store(Request $request) {
        // Request params validation
        $request->validate([
            'name'  => 'required|string|max:200',
            'price' => 'required|int',
        ]);

        $result = $this->_productRepository->store($request->input('name'), $request->input('price'));

        if(empty($result)) {
            return response()->json(['code' => 500, 'message' => 'Product creation failed'], 500);
        }

        return response()->json([
            'code'          => 201,
            'product_created' => $result
        ], 201);
    }
}
