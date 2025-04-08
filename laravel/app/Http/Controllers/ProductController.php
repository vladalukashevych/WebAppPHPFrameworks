<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use function response;

class ProductController extends Controller
{

    public const PRODUCTS = [
        [
            'id'          => '1',
            'name'        => 'product1',
            'description' => 'description',
            'price'       => '123'
        ],
        [
            'id'          => '2',
            'name'        => 'product2',
            'description' => 'description',
            'price'       => '456'
        ],
        [
            'id'          => '3',
            'name'        => 'product3',
            'description' => 'description',
            'price'       => '789'
        ]
    ];

    /**
     * @return mixed
     */
    public function getProducts(): mixed
    {
        return response()->json(self::PRODUCTS,
            Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @return mixed
     */
    public function getProductItem(string $id): mixed
    {
        $product = $this->getProductItemById(self::PRODUCTS, $id);

        if (!$product) {
            return response()->json(['data' => ['error' => 'Product is not found by id. ' . $id]],
                Response::HTTP_NOT_FOUND);
        }

        return response()->json(['data' => $product],
            Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function createProduct(Request $request): mixed
    {
        $requestData = json_decode($request->getContent(), true);

        $productId = random_int(1, 100);

        $newProductData = [
            'id'          => $productId,
            'name'        => $requestData['name'],
            'description' => $requestData['description'],
            'price'       => $requestData['price']
        ];

        // TODO insert to db

        return response()->json([
            'data' => $newProductData
        ], Response::HTTP_CREATED);
    }

    /**
     * @param string $id
     * @return mixed
     */
    public function deleteProduct(string $id): mixed
    {
        $product = $this->getProductItemById(self::PRODUCTS, $id);

        if (!$product) {
            return response()->json(['data' => ['error' => 'Product is not found by id. ' . $id]],
                Response::HTTP_NOT_FOUND);
        }

        // TODO remove from db

        return response()->json([],
            Response::HTTP_NO_CONTENT);
    }

    /**
     * @param array $products
     * @param string $id
     * @return array|null
     */
    public function getProductItemById(array $products, string $id): ?array
    {
        foreach ($products as $product) {
            if ($product['id'] != $id) {
                continue;
            }

            return $product;
        }

        return null;
    }

    /**
     * @param string $id
     * @param Request $request
     * @return mixed
     */
    public function updateProduct(string $id, Request $request): mixed
    {
        $product = $this->getProductItemById(self::PRODUCTS, $id);

        if (!$product) {
            return response()->json([
                'data' => ['error' => 'Product is not found by id. ' . $id]
            ], Response::HTTP_NOT_FOUND);
        }

        // TODO update in db

        return response()->json([
            'data' => ['message' => 'Product updated successfully.']
        ], Response::HTTP_OK);
    }


}
