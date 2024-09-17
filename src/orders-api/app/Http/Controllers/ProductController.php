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
     *     path="/api/v1/products",
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
     *          response=422,
     *          description="Validation error: price field must be an integer.",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="The price field must be an integer."
     *              ),
     *              @OA\Property(property="errors", type="object",
     *                  @OA\Property(property="price", type="array",
     *                      @OA\Items(type="string",example="The price field must be an integer.")
     *                  )
     *              )
     *          )
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

    /**
     * @OA\Get(
     *     path="/api/v1/products/{id}",
     *     summary="Get a product by ID",
     *     description="Returns a product based on the provided ID",
     *     operationId="showProduct",
     *     tags={"Products"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the product to retrieve",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product found",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(
     *                 property="order",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="quidem"),
     *                 @OA\Property(property="price", type="string", example="65.15"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-09-13T15:50:32.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-09-13T15:50:32.000000Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=404),
     *             @OA\Property(property="message", type="string", example="Order not found")
     *         )
     *     )
     * )
     */
    public function show($id) {
        $product = $this->_productRepository->show($id);
        if(!$product) {
            return response()->json(['code' => 404, 'message' => 'Product not found'], 404);
        }

        return response()->json([
            'code'      => 200,
            'product'   => $product
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/products/{id}",
     *     summary="Update a product's price",
     *     description="Updates the price of a product based on the provided ID and price",
     *     operationId="updateProduct",
     *     tags={"Products"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the product to update",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Product price that needs to be updated",
     *         @OA\JsonContent(
     *             required={"price"},
     *             @OA\Property(property="price", type="integer", example=100)
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="No content - Product successfully updated",
     *         @OA\JsonContent(type="string", example=null)
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=404),
     *             @OA\Property(property="message", type="string", example="Product not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Product update failed",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=500),
     *             @OA\Property(property="message", type="string", example="Product update failed")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="price",
     *                     type="array",
     *                     @OA\Items(type="string", example="The price field is required.")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function update(Request $request, $id) {
        // Request params validation
        $request->validate([
            'price' => 'required|integer'
        ]);

        $update_details = $this->_productRepository->update($id, $request->input('price'));
        if($update_details === false) {
            return response()->json(['code' => 500, 'message' => 'Product update failed'], 500);
        }

        if(empty($update_details)) {
            return response()->json(['code' => 404, 'message' => 'Product not found'], 404);
        }

        //No content
        return response()->json(null, 204);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/products/{id}",
     *     summary="Delete a product",
     *     description="Deletes a product based on the provided ID",
     *     operationId="deleteProduct",
     *     tags={"Products"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the product to delete",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="No content - Product successfully deleted",
     *         @OA\JsonContent(type="string", example=null)
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=404),
     *             @OA\Property(property="message", type="string", example="Product not found")
     *         )
     *     ),
     *      @OA\Response(
     *         response=500,
     *         description="Product not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=500),
     *             @OA\Property(property="message", type="string", example="Product delete failed")
     *         )
     *     )
     * )
     */
    public function delete($id) {
        $delete_details = $this->_productRepository->delete($id);
        if($delete_details === false) {
            return response()->json(['code' => 500, 'message' => 'Product delete failed'], 500);
        }

        if(empty($delete_details)) {
            return response()->json(['code'=> 404, 'message' => 'Product not found'], 404);
        }
        return response()->json(null, 204);
    }
}
