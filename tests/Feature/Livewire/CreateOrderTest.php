<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Size;
use Tests\CreateData;
use App\Models\User;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Image;
use Livewire\Livewire;
use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use App\Http\Livewire\AddCartItem;
use App\Http\Livewire\CreateOrder;
use App\Http\Livewire\AddCartItemColor;
use App\Http\Livewire\AddCartItemSize;
use App\Listeners\MergeTheCart;
use App\Models\Order;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Auth\Events\Login;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateOrderTest extends TestCase
{
    use RefreshDatabase, CreateData     ;

    /** @test */
    
    public function a_not_authenticated_user_cannot_create_an_order()
    {
        $this->get('/orders/create')
            ->assertRedirect('/login');

    }
    

    /** @test */
    
    public function an_authenticated_user_can_create_an_order()
    {
        $product = $this->createProduct();

        Livewire::test(AddCartItem::class, ['product' => $product])
            ->call('addItem', $product);
        $this->actingAs(User::factory()->create())->get('/orders/create')->assertStatus(200);

        Livewire::test(CreateOrder::class)->assertSee(Cart::content()->first()->name);

    }
   

    /** @test */

    public function shopping_cart_is_saved_in_database_when_a_user_logs_out()
    {
        $this->actingAs(User::factory()->create());

        $product = $this->createProduct();

        Livewire::test(AddCartItem::class, ['product' => $product])
            ->call('addItem', $product);

        $data = Cart::content();

        $this->post('/logout');

        $this->assertDatabaseHas('shoppingcart', ['content' => serialize($data)]);
    
    }

    /** @test */

    public function shopping_cart_returns_when_a_user_logs_in() 
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $product = $this->createProduct();

        Livewire::test(AddCartItem::class, ['product' => $product])
            ->call('addItem', $product);

        $data = Cart::content();
        $this->post('/logout');

        $listener = new MergeTheCart();
        $event = new Login('web', $user, true);
        $this->actingAs($user);

        $listener->handle($event);

        $this->assertDatabaseHas('shoppingcart', ['content' => serialize($data)]);

    }

    /** @test */
    
    public function test_it_deletes_the_shopping_cart_when_the_order_is_created_and_redirects()
    {
        $this->actingAs(User::factory()->create());

        $product = $this->createProduct();

        Livewire::test(AddCartItem::class, ['product' => $product])
            ->call('addItem', $product);
        $this->assertTrue(count(Cart::content()) != 0);

        Livewire::test(CreateOrder::class)
            ->set('contact', 'contacto')
            ->set('phone', '611111111')
            ->call('create_order')
            ->assertRedirect('/orders/1/payment');

        $this->assertTrue(count(Cart::content()) == 0);
        
    }
}