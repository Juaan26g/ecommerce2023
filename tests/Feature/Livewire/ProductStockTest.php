<?php

namespace Tests\Feature\LivewireTest;

use App\Http\Livewire\AddCartItem;
use App\Http\Livewire\CreateOrder;
use App\Http\Livewire\AddCartItemColor;
use App\Http\Livewire\AddCartItemSize;
use App\Http\Livewire\DropdownCart;
use App\Http\Livewire\Search;
use App\Http\Livewire\ShoppingCart;
use App\Http\Livewire\UpdateCartItem;
use App\Models\Order;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Image;
use App\Models\Product;
use App\Models\Subcategory;
use App\Models\User;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Auth\Events\Logout;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
use Tests\CreateData;

class ProductStockTest extends TestCase
{
    use RefreshDatabase ,CreateData;

  /** @test */
  
  public function ProductsStockChangeWhenAddedToTheCart()
  {
      $Product = $this->createProduct(false, false);

    Livewire::test(AddCartItem::class, ['product' => $Product])
      ->call('addItem', $Product)
      ->assertStatus(200);

      $this->assertEquals(qty_available($Product->id), 4);

  }


  /** @test */
  
  public function colorProductsStockChangeWhenAddedToTheCart()
  {
      $coloredProduct = $this->createProduct(true, false);
      $color = $this->createColor();

      $coloredProduct->colors()->attach($color->id, ['quantity' => 10]);

      Livewire::test(AddCartItemColor::class, ['product' => $coloredProduct])
          ->set('options', ['color_id' => $color->id])
          ->call('addItem', $coloredProduct)
          ->assertStatus(200);

      $this->assertEquals(qty_available($coloredProduct->id, $color->id), 9);
  }


  /** @test */

  public function sizeProductsStockChangeWhenAddedToTheCart()
  {
      $sizedProduct= $this->createProduct(true, true);

      $color = $this->createColor();

      $size = $this->createSize($sizedProduct);

      $size->colors()->attach($color->id, ['quantity' => 10]);


      Livewire::test(AddCartItemSize::class, ['product' => $sizedProduct])
          ->set('options', ['size_id' => $size->id, 'color_id' => $color->id])
          ->call('addItem', $sizedProduct)
          ->assertStatus(200);

      $this->assertEquals(qty_available($sizedProduct->id, $color->id, $size->id), 9);

  }
  

    /** @test */

    public function whenOrderIsCreatedSizeProductStockChangesInDB()
    {
        $product = $this->createProduct(true, true);

        $color = $this->createColor();

        $size = $this->createSize($product);

        $size->colors()->attach($color->id, ['quantity' => 10]);

        $user = $this->createUser();
        $this->actingAs($user);

        Livewire::test(AddCartItemSize::class, ['product' => $product])
            ->set('options', ['size_id' => $size->id, 'color_id' => $color->id])
            ->call('addItem', $product)
            ->assertStatus(200);

        Livewire::test(CreateOrder::class,['contact' => 'Test', 'phone' => 633444816])
            ->call('create_order')
            ->assertStatus(200);

        $this->assertDatabaseHas('color_size', [
            'quantity' => 9
        ]);
    }

     /** @test */

     public function checksTheExpirationOfPendingOrders()
     {
        $product1 = $this->createProduct();
        $this->actingAs(User::factory()->create());

        Livewire::test(AddCartItem::class, ['product' => $product1])
            ->call('addItem', $product1);

        Livewire::test(CreateOrder::class)
            ->set('contact', 'Juan ')
            ->set('phone', '678837294')
            ->call('create_order');


        $order = Order::first();
        $order->created_at = now()->subMinute(15);
        $order->save();

        $this->artisan('schedule:run');
        $order = Order::first();
        $this->assertEquals($order->status, 5);

     }
     
} 
