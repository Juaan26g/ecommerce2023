<?php

namespace Tests\Feature;

use Tests\TestCase;
use Tests\CreateData;
use Livewire\Livewire;
use App\Http\Livewire\AddCartItem;
use App\Http\Livewire\ShoppingCart;
use App\Http\Livewire\UpdateCartItem;
use App\Http\Livewire\AddCartItemSize;
use App\Http\Livewire\AddCartItemColor;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CartViewTest extends TestCase
{
    use RefreshDatabase, CreateData;

    /** @test */
    
    public function itDisplaysAddedProductsToShoppingCart() 
    {
        $product = $this->createProduct();
        $product1 = $this->createProduct();
        $product2 = $this->createProduct(true);
        $product3 = $this->createProduct(true, true);

        Livewire::test(AddCartItem::class, ['product' => $product1])
            ->call('addItem', $product1);

        Livewire::test(AddCartItemColor::class, ['product' => $product2])
            ->call('addItem', $product2);

        Livewire::test(AddCartItemSize::class, ['product' => $product3])
            ->call('addItem', $product);

        $response = $this->get('/shopping-cart');

        $response->assertStatus(200)
            ->assertDontSee($product->name)
            ->assertSee($product1->name)
            ->assertSee($product2->name)
            ->assertSee($product3->name);

    }
    /** @test */
    
    public function CanIncrementAndDecrementProductWithoutColorOrSizeQuantity() 
    {
        $product = $this->createProduct();

        Livewire::test(AddCartItem::class, ['product' => $product])
            ->call('addItem', $product);

        $total = Cart::subtotal();

        Livewire::test(UpdateCartItem::class, ['rowId' => Cart::content()->first()->rowId])
            ->call('increment');
        $this->assertEquals($total * 2, Cart::subtotal());

        Livewire::test(UpdateCartItem::class, ['rowId' => Cart::content()->first()->rowId])
            ->call('decrement');
        $this->assertEquals($total, Cart::subtotal());

    }

    /** @test */
    
    public function itCanIncrementAndDecrementProductWithColor()
    {
        $product1 = $this->createProduct(true, false);

        Livewire::test(AddCartItem::class, ['product' => $product1])
            ->call('addItem', $product1);

        $total = Cart::subtotal();

        Livewire::test(UpdateCartItem::class, ['rowId' => Cart::content()->first()->rowId])
            ->call('increment')
            ->call('increment');
        $this->assertEquals($total * 3, Cart::subtotal());

        Livewire::test(UpdateCartItem::class, ['rowId' => Cart::content()->first()->rowId])
            ->call('decrement')
            ->call('decrement');
        $this->assertEquals($total, Cart::subtotal());

    }


    /** @test */

    public function itCanIncrementAndDecrementProductWithColorAndSize()
       {
    $product1 = $this->createProduct(true, false);

        Livewire::test(AddCartItem::class, ['product' => $product1])
            ->call('addItem', $product1);

        $total = Cart::subtotal();

        Livewire::test(UpdateCartItem::class, ['rowId' => Cart::content()->first()->rowId])
            ->call('increment')
            ->call('increment');
        $this->assertEquals($total * 3, Cart::subtotal());

        Livewire::test(UpdateCartItem::class, ['rowId' => Cart::content()->first()->rowId])
            ->call('decrement')
            ->call('decrement');
        $this->assertEquals($total, Cart::subtotal());

    }


    /** @test */

    public function itCanDeleteAProductFromTheCart()
    {
        $product = $this->createProduct();
        $product2 = $this->createProduct();

        Livewire::test(AddCartItem::class, ['product' => $product])
            ->call('addItem', $product);
        Livewire::test(AddCartItem::class, ['product' => $product2])
            ->call('addItem', $product2);

        Livewire::test(ShoppingCart::class)
            ->call('delete', Cart::content()->first()->rowId);

        $this->assertTrue(count(Cart::content()) == 1);
        
    }
    

    /** @test */
    
    public function itCanDeleteTheShoppingCart()
    {
        $product = $this->createProduct();
        $product2 = $this->createProduct();

        Livewire::test(AddCartItem::class, ['product' => $product])
            ->call('addItem', $product);
        Livewire::test(AddCartItem::class, ['product' => $product2])
            ->call('addItem', $product2);

        Livewire::test(ShoppingCart::class)
            ->call('destroy', Cart::content()->first()->rowId);

        $this->assertTrue(count(Cart::content()) == 0);

    }
}