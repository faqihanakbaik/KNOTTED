<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductVariation;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\AddToCartRequest;
use App\Http\Requests\CheckoutRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;

class ValidationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function product_validation_requires_necessary_fields()
    {
        $data = [];
        
        $rules = (new StoreProductRequest())->rules();
        $validator = Validator::make($data, $rules);
        
        $this->assertFalse($validator->passes());
        $this->assertTrue($validator->errors()->has('name'));
        $this->assertTrue($validator->errors()->has('slug'));
        $this->assertTrue($validator->errors()->has('description'));
        $this->assertTrue($validator->errors()->has('price'));
        $this->assertTrue($validator->errors()->has('category_id'));
    }

    /** @test */
    public function product_price_cannot_be_negative()
    {
        $category = Category::factory()->create();
        
        $data = [
            'name' => 'Test Product',
            'slug' => 'test-product',
            'description' => 'This is a test product description that is long enough.',
            'price' => -100,
            'category_id' => $category->id,
            'product_type' => 'ready_stock',
        ];
        
        $rules = (new StoreProductRequest())->rules();
        $validator = Validator::make($data, $rules);
        
        $this->assertFalse($validator->passes());
        $this->assertTrue($validator->errors()->has('price'));
    }

    /** @test */
    public function product_description_must_be_minimum_length()
    {
        $category = Category::factory()->create();
        
        $data = [
            'name' => 'Test Product',
            'slug' => 'test-product',
            'description' => 'Short', // Too short
            'price' => 100000,
            'category_id' => $category->id,
            'product_type' => 'ready_stock',
        ];
        
        $rules = (new StoreProductRequest())->rules();
        $validator = Validator::make($data, $rules);
        
        $this->assertFalse($validator->passes());
        $this->assertTrue($validator->errors()->has('description'));
    }

    /** @test */
    public function add_to_cart_validation_requires_product_and_quantity()
    {
        $data = [];
        
        $rules = (new AddToCartRequest())->rules();
        $validator = Validator::make($data, $rules);
        
        $this->assertFalse($validator->passes());
        $this->assertTrue($validator->errors()->has('product_id'));
        $this->assertTrue($validator->errors()->has('quantity'));
    }

    /** @test */
    public function cart_quantity_must_be_positive()
    {
        $product = Product::factory()->create();
        
        $data = [
            'product_id' => $product->id,
            'quantity' => 0, // Invalid
        ];
        
        $rules = (new AddToCartRequest())->rules();
        $validator = Validator::make($data, $rules);
        
        $this->assertFalse($validator->passes());
        $this->assertTrue($validator->errors()->has('quantity'));
    }

    /** @test */
    public function cart_quantity_cannot_exceed_maximum()
    {
        $product = Product::factory()->create();
        
        $data = [
            'product_id' => $product->id,
            'quantity' => 101, // Exceeds max
        ];
        
        $rules = (new AddToCartRequest())->rules();
        $validator = Validator::make($data, $rules);
        
        $this->assertFalse($validator->passes());
        $this->assertTrue($validator->errors()->has('quantity'));
    }

    /** @test */
    public function checkout_requires_shipping_information()
    {
        $data = [
            'payment_method' => 'bank_transfer',
        ];
        
        $rules = (new CheckoutRequest())->rules();
        $validator = Validator::make($data, $rules);
        
        $this->assertFalse($validator->passes());
        $this->assertTrue($validator->errors()->has('shipping_address'));
        $this->assertTrue($validator->errors()->has('shipping_city'));
        $this->assertTrue($validator->errors()->has('shipping_province'));
        $this->assertTrue($validator->errors()->has('shipping_postal_code'));
        $this->assertTrue($validator->errors()->has('shipping_phone'));
    }

    /** @test */
    public function checkout_validates_phone_number_format()
    {
        $data = [
            'payment_method' => 'bank_transfer',
            'shipping_address' => '123 Test Street',
            'shipping_city' => 'Jakarta',
            'shipping_province' => 'DKI Jakarta',
            'shipping_postal_code' => '12345',
            'shipping_phone' => 'invalid-phone', // Invalid format
            'shipping_cost' => 20000,
        ];
        
        $rules = (new CheckoutRequest())->rules();
        $validator = Validator::make($data, $rules);
        
        $this->assertFalse($validator->passes());
        $this->assertTrue($validator->errors()->has('shipping_phone'));
    }

    /** @test */
    public function validation_accepts_valid_product_data()
    {
        $category = Category::factory()->create();
        
        $data = [
            'name' => 'Crochet Bucket Hat',
            'slug' => 'crochet-bucket-hat',
            'description' => 'Beautiful handmade crochet bucket hat perfect for summer.',
            'price' => 150000,
            'category_id' => $category->id,
            'product_type' => 'ready_stock',
            'is_active' => true,
        ];
        
        $rules = (new StoreProductRequest())->rules();
        $validator = Validator::make($data, $rules);
        
        $this->assertTrue($validator->passes());
    }

    /** @test */
    public function validation_accepts_valid_cart_data()
    {
        $product = Product::factory()->create();
        
        $data = [
            'product_id' => $product->id,
            'quantity' => 2,
            'notes' => 'Please pack carefully',
        ];
        
        $rules = (new AddToCartRequest())->rules();
        $validator = Validator::make($data, $rules);
        
        $this->assertTrue($validator->passes());
    }
}
