<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\IndexProductRequest;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->authorizeResource(Product::class);
    }

    public function index(IndexProductRequest $request): AnonymousResourceCollection
    {
        return ProductResource::collection(
            Product::query()->where('name', 'like', '%' . $request->input('q') . '%')->paginate(10)
        );
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        $validatedData = $request->validated();
        $imagePath = $request->file('image')->store('products', 'public');
        $validatedData['image'] = $imagePath;
        $validatedData['price'] = (int) ($validatedData['price'] * 100);

        Product::query()->create($validatedData);

        return response()->json([], 201);
    }

    public function show(Product $product): ProductResource
    {
        return new ProductResource($product);
    }

    public function update(UpdateProductRequest $request, Product $product): ProductResource
    {
        $validatedData = $request->validated();

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $validatedData['image'] = $imagePath;
        }
        $validatedData['price'] = (int) ($validatedData['price'] * 100);

        $product->update($validatedData);

        return new ProductResource($product);
    }

    public function destroy(Product $product): JsonResponse
    {
        $product->delete();

        return response()->json([], 204);
    }
}
