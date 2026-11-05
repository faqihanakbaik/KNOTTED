<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Cart;
use App\Models\CustomOrder;
use App\Policies\ProductPolicy;
use App\Policies\OrderPolicy;
use App\Policies\CartPolicy;
use App\Policies\CustomOrderPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $customer;
    protected User $otherCustomer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->customer = User::factory()->create(['role' => 'customer']);
        $this->otherCustomer = User::factory()->create(['role' => 'customer']);
    }

    /** @test */
    public function admin_can_create_products()
    {
        $policy = new ProductPolicy();
        
        $this->assertTrue($policy->create($this->admin));
        $this->assertFalse($policy->create($this->customer));
    }

    /** @test */
    public function admin_can_update_products()
    {
        $product = Product::factory()->create();
        $policy = new ProductPolicy();
        
        $this->assertTrue($policy->update($this->admin, $product));
        $this->assertFalse($policy->update($this->customer, $product));
    }

    /** @test */
    public function customer_can_only_view_their_own_orders()
    {
        $customerOrder = Order::factory()->create(['user_id' => $this->customer->id]);
        $otherOrder = Order::factory()->create(['user_id' => $this->otherCustomer->id]);
        
        $policy = new OrderPolicy();
        
        // Customer can view their own order
        $this->assertTrue($policy->view($this->customer, $customerOrder));
        
        // Customer cannot view other customer's order
        $this->assertFalse($policy->view($this->customer, $otherOrder));
        
        // Admin can view all orders
        $this->assertTrue($policy->view($this->admin, $customerOrder));
        $this->assertTrue($policy->view($this->admin, $otherOrder));
    }

    /** @test */
    public function customer_can_upload_payment_proof_for_own_order()
    {
        $order = Order::factory()->create([
            'user_id' => $this->customer->id,
            'status' => 'pending_payment',
        ]);
        
        $otherOrder = Order::factory()->create([
            'user_id' => $this->otherCustomer->id,
            'status' => 'pending_payment',
        ]);
        
        $policy = new OrderPolicy();
        
        // Customer can upload proof for their own order
        $this->assertTrue($policy->uploadPaymentProof($this->customer, $order));
        
        // Customer cannot upload proof for other customer's order
        $this->assertFalse($policy->uploadPaymentProof($this->customer, $otherOrder));
        
        // Admin cannot upload payment proof (customer-only action)
        $this->assertFalse($policy->uploadPaymentProof($this->admin, $order));
    }

    /** @test */
    public function only_admin_can_verify_payments()
    {
        $order = Order::factory()->create([
            'user_id' => $this->customer->id,
            'status' => 'verifying_payment',
        ]);
        
        $policy = new OrderPolicy();
        
        $this->assertTrue($policy->verifyPayment($this->admin, $order));
        $this->assertFalse($policy->verifyPayment($this->customer, $order));
    }

    /** @test */
    public function customer_can_only_access_their_own_cart()
    {
        $customerCart = Cart::factory()->create(['user_id' => $this->customer->id]);
        $otherCart = Cart::factory()->create(['user_id' => $this->otherCustomer->id]);
        
        $policy = new CartPolicy();
        
        // Customer can view their own cart
        $this->assertTrue($policy->view($this->customer, $customerCart));
        
        // Customer cannot view other customer's cart
        $this->assertFalse($policy->view($this->customer, $otherCart));
        
        // Admin cannot access customer carts (cart is customer-only feature)
        $this->assertFalse($policy->view($this->admin, $customerCart));
    }

    /** @test */
    public function customer_can_create_custom_orders()
    {
        $policy = new CustomOrderPolicy();
        
        $this->assertTrue($policy->create($this->customer));
        $this->assertFalse($policy->create($this->admin));
    }

    /** @test */
    public function customer_can_only_view_their_own_custom_orders()
    {
        $customerCustomOrder = CustomOrder::factory()->create(['user_id' => $this->customer->id]);
        $otherCustomOrder = CustomOrder::factory()->create(['user_id' => $this->otherCustomer->id]);
        
        $policy = new CustomOrderPolicy();
        
        // Customer can view their own custom order
        $this->assertTrue($policy->view($this->customer, $customerCustomOrder));
        
        // Customer cannot view other customer's custom order
        $this->assertFalse($policy->view($this->customer, $otherCustomOrder));
        
        // Admin can view all custom orders
        $this->assertTrue($policy->view($this->admin, $customerCustomOrder));
        $this->assertTrue($policy->view($this->admin, $otherCustomOrder));
    }

    /** @test */
    public function only_admin_can_respond_to_custom_orders()
    {
        $customOrder = CustomOrder::factory()->create(['user_id' => $this->customer->id]);
        
        $policy = new CustomOrderPolicy();
        
        $this->assertTrue($policy->respond($this->admin, $customOrder));
        $this->assertFalse($policy->respond($this->customer, $customOrder));
    }

    /** @test */
    public function customer_can_approve_offers_for_their_own_custom_orders()
    {
        $customOrder = CustomOrder::factory()->create([
            'user_id' => $this->customer->id,
            'status' => 'quoted',
        ]);
        
        $otherCustomOrder = CustomOrder::factory()->create([
            'user_id' => $this->otherCustomer->id,
            'status' => 'quoted',
        ]);
        
        $policy = new CustomOrderPolicy();
        
        // Customer can approve their own custom order offer
        $this->assertTrue($policy->approveOffer($this->customer, $customOrder));
        
        // Customer cannot approve other customer's custom order offer
        $this->assertFalse($policy->approveOffer($this->customer, $otherCustomOrder));
        
        // Admin cannot approve offers (customer decision)
        $this->assertFalse($policy->approveOffer($this->admin, $customOrder));
    }

    /** @test */
    public function inactive_products_only_visible_to_admin()
    {
        $activeProduct = Product::factory()->create(['is_active' => true]);
        $inactiveProduct = Product::factory()->create(['is_active' => false]);
        
        $policy = new ProductPolicy();
        
        // Everyone can view active products
        $this->assertTrue($policy->view(null, $activeProduct)); // Guest
        $this->assertTrue($policy->view($this->customer, $activeProduct));
        $this->assertTrue($policy->view($this->admin, $activeProduct));
        
        // Only admin can view inactive products
        $this->assertFalse($policy->view(null, $inactiveProduct)); // Guest
        $this->assertFalse($policy->view($this->customer, $inactiveProduct));
        $this->assertTrue($policy->view($this->admin, $inactiveProduct));
    }

    /** @test */
    public function admin_can_manage_stock()
    {
        $product = Product::factory()->create();
        $policy = new ProductPolicy();
        
        $this->assertTrue($policy->manageStock($this->admin, $product));
        $this->assertFalse($policy->manageStock($this->customer, $product));
    }
}
