<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperProduct
 */
class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function newFactory(): ProductFactory
    {
        return ProductFactory::new();
    }

    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => str_starts_with($value, 'https://prd.place') ? $value : asset('storage/' . $value),
        );
    }
}
