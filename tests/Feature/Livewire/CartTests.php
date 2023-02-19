<?php

namespace Tests\Feature\Livewire;

use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Livewire\{AddCartItem,AddCartItemColor,AddCartItemSize};
use Livewire\Livewire;
use Tests\TestCase;
use Tests\CreateData;

class CartTests extends TestCase
{
    use CreateData, RefreshDatabase;
    /** @Test */

    public function test_base_products_are_added_to_the_cart() 
    {
        $Product = $this->createProduct(false, false);

        Livewire::test(AddCartItem::class, ['product' => $Product])
            ->call('addItem', $Product)
            ->assertStatus(200);

        $this->assertEquals(Cart::content()->first()->name, $Product->name);
    }
    /** @test */

    public function test_products_with_only_color_are_added_to_the_cart() 
    {
        $colorProduct = $this->createProduct(true, false);

        Livewire::test(AddCartItemColor::class, ['product' => $colorProduct])
            ->call('addItem', $colorProduct);


        $this->assertEquals(Cart::content()->first()->name, $colorProduct->name);
    }
    /** @test */

    public function test_products_with_size_and_color_are_added_to_the_cart() 
    {
        $sizedProduct = $this->createProduct(true, true);

        Livewire::test(AddCartItemSize::class, ['product' => $sizedProduct])
            ->call('addItem', $sizedProduct);


        $this->assertEquals(Cart::content()->first()->name, $sizedProduct->name);
    }
}
