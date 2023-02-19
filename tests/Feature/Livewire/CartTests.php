<?php

namespace Tests\Feature\Livewire;

use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Livewire\{AddCartItem,AddCartItemColor,AddCartItemSize,DropdownCart};
use Livewire\Livewire;
use Tests\TestCase;
use Tests\CreateData;

class CartTests extends TestCase
{
    use CreateData, RefreshDatabase;
    /** @Test */

    public function productsAreAddedToTheCart() 
    {
        $Product = $this->createProduct(false, false);

        Livewire::test(AddCartItem::class, ['product' => $Product])
            ->call('addItem', $Product)
            ->assertStatus(200);

        $this->assertEquals(Cart::content()->first()->name, $Product->name);
    }
    /** @test */

    public function colorProductsAreAddedToTheCart() 
    {
        $colorProduct = $this->createProduct(true, false);

        Livewire::test(AddCartItemColor::class, ['product' => $colorProduct])
            ->call('addItem', $colorProduct);


        $this->assertEquals(Cart::content()->first()->name, $colorProduct->name);
    }
    /** @test */

    public function sizeProductsAreAddedToTheCart() 
    {
        $sizedProduct = $this->createProduct(true, true);

        Livewire::test(AddCartItemSize::class, ['product' => $sizedProduct])
            ->call('addItem', $sizedProduct);


        $this->assertEquals(Cart::content()->first()->name, $sizedProduct->name);
    }

     /** @test */
    
     public function addedProductsAreSeenByClickingInTheCart() 
     {
         $addedProduct = $this->createProduct(false, false);
         $addedProduct2 = $this->createProduct(true, false);
         $addedProduct3 = $this->createProduct(true, true);
         $notAddedProduct = $this->createProduct(true, true);
 
         Livewire::test(AddCartItem::class, ['product' => $addedProduct])
             ->call('addItem', $addedProduct);
 
         Livewire::test(AddCartItem::class, ['product' => $addedProduct2])
             ->call('addItem', $addedProduct2);    
             
         Livewire::test(AddCartItem::class, ['product' => $addedProduct3])
             ->call('addItem', $addedProduct3);   
         
         Livewire::test(DropdownCart::class, ['product' => $addedProduct])
             ->assertSee($addedProduct->name)
             ->assertSee($addedProduct2->name)
             ->assertSee($addedProduct3->name)
             ->assertDontSee($notAddedProduct->name);    
             
     }
}
