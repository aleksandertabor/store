<?php

namespace Tests\Feature\Api;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use DatabaseMigrations;

    public function test_index(): void
    {
        $product = Product::factory()->create();

        $response = $this->getJson(route('api.products.index'));

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'name' => $product->name,
            'description' => $product->description,
            'price' => number_format($product->price / 100, 2),
            'stock' => $product->stock,
            'rank' => $product->rank,
            'image' => $product->image,
        ]);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'name',
                    'description',
                    'price',
                    'stock',
                    'rank',
                    'image',
                ],
            ],
            'meta' => [
                'current_page',
                'from',
                'last_page',
                'links',
                'path',
                'per_page',
                'to',
                'total',
            ],
            'links' => [
                'first',
                'last',
                'prev',
                'next',
            ],
        ]);
    }

    public function test_store(): void
    {
        $this->actingAs(User::factory()->create());

        $fakeImage = UploadedFile::fake()->image('product.jpg');

        $response = $this->postJson(route('api.products.store'), [
            'name' => 'Test Product',
            'description' => 'Test Product Description',
            'price' => 100,
            'stock' => 100,
            'rank' => 1,
            'image' => $fakeImage,
        ]);

        $response->assertStatus(201);
        $response->assertJsonFragment([]);
    }

    public function test_show(): void
    {
        $product = Product::factory()->create();

        $response = $this->getJson(route('api.products.show', $product->id));

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'name' => $product->name,
            'description' => $product->description,
            'price' => number_format($product->price / 100, 2),
            'stock' => $product->stock,
            'rank' => $product->rank,
            'image' => $product->image,
        ]);
    }

    public function test_update(): void
    {
        $this->actingAs(User::factory()->create());

        $product = Product::factory()->create();

        $fakeImage = UploadedFile::fake()->image('product.jpg');

        $response = $this->patchJson(route('api.products.update', $product->id), [
            'name' => 'Test Product',
            'description' => 'Test Product Description',
            'price' => 100.00,
            'stock' => 100,
            'rank' => 1,
            'image' => $fakeImage,
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'name' => 'Test Product',
            'description' => 'Test Product Description',
            'price' => number_format(10000 / 100, 2),
            'stock' => 100,
            'rank' => 1,
            'image' => Storage::disk('public')->url('products/' . $fakeImage->hashName()),
        ]);
    }

    public function test_destroy(): void
    {
        $this->actingAs(User::factory()->create());

        $product = Product::factory()->create();

        $response = $this->deleteJson(route('api.products.destroy', $product->id));

        $response->assertStatus(204);
    }
}
