<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\OrderRepositoryInterface;
use App\Interfaces\ProductRepositoryInterface;

class OrderController extends Controller {

    /**
     * Order repository interface
     */
    private $_orderRepository;

    /**
     * Class constructor
     *
     * @return void
     */
    public function __construct(OrderRepositoryInterface $orderRepositoryInterface) {
        $this->_orderRepository = $orderRepositoryInterface;
    }

    /**
     * @OA\Get(
     *     path="/api/v1/orders",
     *     summary="Get a list of orders with optional filters",
     *     tags={"Orders"},
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         required=false,
     *         description="Filter orders by name",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="description",
     *         in="query",
     *         required=false,
     *         description="Filter orders by description",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="date",
     *         in="query",
     *         required=false,
     *         description="Filter orders by date",
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="A list of orders",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="code",type="integer",example=200),
     *             @OA\Property(
     *                 property="orders",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="voluptas"),
     *                     @OA\Property(property="description", type="string", example="Et cupiditate aut est sed nobis."),
     *                     @OA\Property(property="date", type="string", format="date-time", example="1978-02-13T00:00:00Z"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2024-09-11T07:50:15.000000Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-09-11T07:50:15.000000Z"),
     *                     @OA\Property(
     *                         property="products",
     *                         type="array",
     *                         @OA\Items(
     *                             type="object",
     *                             @OA\Property(property="id", type="integer", example=56),
     *                             @OA\Property(property="name", type="string", example="est"),
     *                             @OA\Property(property="price", type="string", example="23.43"),
     *                             @OA\Property(property="created_at", type="string", format="date-time", example="2024-09-11T07:50:15.000000Z"),
     *                             @OA\Property(property="updated_at", type="string", format="date-time", example="2024-09-11T07:50:15.000000Z"),
     *                             @OA\Property(
     *                                 property="pivot",
     *                                 type="object",
     *                                 @OA\Property(property="order_id", type="integer", example=1),
     *                                 @OA\Property(property="product_id", type="integer", example=56),
     *                                 @OA\Property(property="quantity", type="integer", example=4),
     *                                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-09-11T07:50:15.000000Z"),
     *                                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-09-11T07:50:15.000000Z")
     *                             )
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Invalid parameters",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="code", type="integer", example=400),
     *             @OA\Property(property="message", type="string", example="Invalid parameters")
     *         )
     *     )
     * )
     */
    public function index(Request $request) {
        return response()->json([
            'code'      => 200,
            'orders'    => $this->_orderRepository->index($request)
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/orders",
     *     summary="Store a newly created order in the database along with its products. Request expects an array of products with quantity",
     *     tags={"Orders"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", description="The name of the order", example="New Order Name"),
     *             @OA\Property(property="description", type="string", description="Description of the order", example="Description of the new order."),
     *             @OA\Property(property="date", type="string", format="date", description="The date of the order", example="2024-09-15"),
     *             @OA\Property(
     *                 property="products",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="product_id", type="integer", description="ID of the product", example=56),
     *                     @OA\Property(property="quantity", type="integer", description="Quantity of the product", example=4)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
    *         response="201",
    *         description="Order created successfully",
    *         @OA\JsonContent(
    *             type="object",
    *             @OA\Property(property="code", type="integer", example=201),
    *             @OA\Property(
    *                 property="order_created",
    *                 type="object",
    *                 description="Details of the created order",
    *                 @OA\Property(property="id", type="integer", example=1),
    *                 @OA\Property(property="name", type="string", example="New Order Name"),
    *                 @OA\Property(property="description", type="string", example="Description of the new order."),
    *                 @OA\Property(property="date", type="string", format="date-time", example="2024-09-15T00:00:00Z"),
    *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-09-11T07:50:15.000000Z"),
    *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-09-11T07:50:15.000000Z"),
    *                 @OA\Property(
    *                     property="products",
    *                     type="array",
    *                     @OA\Items(
    *                         type="object",
    *                         @OA\Property(property="id", type="integer", example=37),
    *                         @OA\Property(property="name", type="string", example="perferendis"),
    *                         @OA\Property(property="price", type="string", example="10.88"),
    *                         @OA\Property(property="created_at", type="string", format="date-time", example="2024-09-11T07:50:15.000000Z"),
    *                         @OA\Property(property="updated_at", type="string", format="date-time", example="2024-09-11T07:50:15.000000Z"),
    *                         @OA\Property(
    *                             property="pivot",
    *                             type="object",
    *                             @OA\Property(property="order_id", type="integer", example=1),
    *                             @OA\Property(property="product_id", type="integer", example=37),
    *                             @OA\Property(property="quantity", type="integer", example=4),
    *                             @OA\Property(property="created_at", type="string", format="date-time", example="2024-09-11T07:50:15.000000Z"),
    *                             @OA\Property(property="updated_at", type="string", format="date-time", example="2024-09-11T07:50:15.000000Z")
    *                         )
    *                     )
    *                 )
    *             )
    *         )
    *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Invalid input parameters",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="code", type="integer", example=400),
     *             @OA\Property(property="message", type="string", example="Invalid input parameters")
     *         )
     *     ),
     *     @OA\Response(
     *          response=422,
     *          description="Validation error: name field must be a string.",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="The name field must be a string."
     *              ),
     *              @OA\Property(property="errors", type="object",
     *                  @OA\Property(property="name", type="array",
     *                      @OA\Items(type="string",example="The name field must be a string.")
     *                  )
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Order creation failed",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="code", type="integer", example=500),
     *             @OA\Property(property="message", type="string", example="Order creation failed")
     *         )
     *     )
     * )
     */
    public function store(Request $request) {
        // Request params validation
        $request->validate([
            'name'                  => 'required|string|max:200',
            'description'           => 'required|string',
            'date'                  => 'required|date',
            'products'              => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity'   => 'required|integer|min:1',
        ]);

        $result = $this->_orderRepository->store(
            $request->input('name'),
            $request->input('description'),
            $request->input('date'),
            $request->products
        );

        if(empty($result)) {
            return response()->json(['code' => 500, 'message' => 'Order creation failed'], 500);
        }

        return response()->json([
            'code'          => 201,
            'order_created' => $result
        ],201);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/orders/{id}",
     *     summary="Display the specified order along with its associated products by ID",
     *     tags={"Orders"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the order to retrieve",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Details of the specified order",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="code",type="integer",example=200),
     *             @OA\Property(
     *                 property="orders",
     *                 type="array",
     *                 @OA\Items(
     *                      @OA\Property(property="code",type="integer",example=200),
     *                      @OA\Property(property="id",type="integer",example=2),
     *                      @OA\Property(property="name",type="string",example="aut"),
     *                      @OA\Property(property="description",type="string",example="Nam placeat quidem et omnis inventore."),
     *                      @OA\Property(property="date", type="string",format="date-time",example="2018-03-31T00:00:00Z"),
     *                      @OA\Property( property="created_at",type="string",format="date-time",example="2024-09-11T07:50:15.000000Z"),
     *                      @OA\Property(property="updated_at",type="string",format="date-time",example="2024-09-11T07:50:15.000000Z"),
     *                      @OA\Property(
     *                          property="products",
     *                          type="array",
     *                          @OA\Items(
     *                              type="object",
     *                              @OA\Property(property="id",type="integer",example=37),
     *                              @OA\Property(property="name",type="string",example="perferendis"),
     *                              @OA\Property(property="price",type="string",example="10.88"),
     *                              @OA\Property(property="created_at",type="string",format="date-time",example="2024-09-11T07:50:15.000000Z"),
     *                              @OA\Property(property="updated_at",type="string",format="date-time",example="2024-09-11T07:50:15.000000Z"),
     *                              @OA\Property(
     *                                  property="pivot",
     *                                  type="object",
     *                                  @OA\Property(property="order_id",type="integer",example=2),
     *                                  @OA\Property(property="product_id",type="integer",example=37),
     *                                  @OA\Property(property="quantity",type="integer",example=10),
     *                                  @OA\Property(property="created_at",type="string",format="date-time",example="2024-09-11T07:50:15.000000Z"),
     *                                  @OA\Property(property="updated_at",type="string",format="date-time",example="2024-09-11T07:50:15.000000Z")
     *                              )
     *                          )
     *                      )
     *                  )
     *              )
     *         )
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Order not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="code", type="integer", example=404),
     *             @OA\Property(property="message", type="string", example="Order not found")
     *         )
     *     )
     * )
     */
    public function show($order_id) {
        $order = $this->_orderRepository->show($order_id);

        if(!$order) {
            return response()->json(['code' => 404, 'message' => 'Order not found'], 404);
        }

        return response()->json([
            'code'  => 200,
            'order' => $order
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/orders/{id}",
     *     summary="Update the specified order and its associated products. Request expects an array of products with quantity.",
     *     tags={"Orders"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the order to update",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", description="The name of the order", example="Updated Order Name"),
     *             @OA\Property(property="description", type="string", description="Description of the order", example="Updated description for the order."),
     *             @OA\Property(property="order_date", type="string", format="date", description="The date of the order", example="2024-09-15"),
     *             @OA\Property(
     *                 property="products",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="product_id", type="integer", description="ID of the product", example=56),
     *                     @OA\Property(property="quantity", type="integer", description="Quantity of the product", example=4)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Order updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(
     *                 property="order_update",
     *                 type="object",
     *                 description="Updated order details",
     *                 @OA\Property(property="id", type="integer", example=2),
     *                 @OA\Property(property="name", type="string", example="Updated Order Name"),
     *                 @OA\Property(property="description", type="string", example="Updated description for the order."),
     *                 @OA\Property(property="date", type="string", format="date-time", example="2024-09-15T00:00:00Z"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-09-11T07:50:15.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-09-11T07:50:15.000000Z"),
     *                 @OA\Property(
     *                     property="products",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=37),
     *                         @OA\Property(property="name", type="string", example="perferendis"),
     *                         @OA\Property(property="price", type="string", example="10.88"),
     *                         @OA\Property(property="created_at", type="string", format="date-time", example="2024-09-11T07:50:15.000000Z"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time", example="2024-09-11T07:50:15.000000Z"),
     *                         @OA\Property(
     *                             property="pivot",
     *                             type="object",
     *                             @OA\Property(property="order_id", type="integer", example=2),
     *                             @OA\Property(property="product_id", type="integer", example=37),
     *                             @OA\Property(property="quantity", type="integer", example=10),
     *                             @OA\Property(property="created_at", type="string", format="date-time", example="2024-09-11T07:50:15.000000Z"),
     *                             @OA\Property(property="updated_at", type="string", format="date-time", example="2024-09-11T07:50:15.000000Z")
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Invalid input parameters",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="code", type="integer", example=400),
     *             @OA\Property(property="message", type="string", example="Invalid input parameters")
     *         )
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Order not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="code", type="integer", example=404),
     *             @OA\Property(property="message", type="string", example="Order not found")
     *         )
     *     ),
     *     @OA\Response(
     *          response=422,
     *          description="Validation error: name field must be a string.",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="The name field must be a string."
     *              ),
     *              @OA\Property(property="errors", type="object",
     *                  @OA\Property(property="name", type="array",
     *                      @OA\Items(type="string",example="The name field must be a string.")
     *                  )
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Order update failed",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="code", type="integer", example=500),
     *             @OA\Property(property="message", type="string", example="Order update failed")
     *         )
     *     )
     * )
     */
    public function update(Request $request, $order_id) {
        // Request params validation
        $request->validate([
            'name'                  => 'sometimes|required|string|max:255',
            'description'           => 'sometimes|required|string',
            'date'                  => 'sometimes|required|date',
            'products'              => 'sometimes|required|array',
            'products.*.product_id' => 'required_with:products|exists:products,id',
            'products.*.quantity'   => 'required_with:products|integer|min:1',
        ]);

        // If products are provided pass to sync
        $products = [];
        if ($request->has('products')) {
            $products = $request->products;
        }

        //Update
        $update_details = $this->_orderRepository->update(
            $order_id,
            $request->input('name'),
            $request->input('description'),
            $request->input('date'),
            $products
        );

        if($update_details === false) {
            return response()->json(['code' => 500, 'message' => 'Order update failed'], 500);
        }

        if(empty($update_details)) {
            return response()->json(['code' => 404, 'message' => 'Order not found'], 404);
        }

        return response()->json([
            'code'          => 200,
            'order_update'  => $update_details
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/orders/{id}",
     *     summary="Remove the specified order from the database along with its product associations.",
     *     tags={"Orders"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the order to delete",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response="204",
     *         description="Order deleted successfully. No content Response",
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Order not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="code", type="integer", example=404),
     *             @OA\Property(property="message", type="string", example="Order not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="OOrder delete failed",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="code", type="integer", example=500),
     *             @OA\Property(property="message", type="string", example="Order delete failed")
     *         )
     *     )
     * )
     */
    public function delete($order_id) {
        $delete_details = $this->_orderRepository->delete($order_id);

        if($delete_details === false) {
            return response()->json(['code' => 500, 'message' => 'Order delete failed'], 500);
        }

        if(empty($delete_details)) {
            return response()->json(['code' => 404, 'message' => 'Order not found'], 404);
        }
        return response()->json(null, 204);
    }
}
