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
    
    public function aNotAuthenticatedUserCannotCreateAnOrder()
    {
        $this->get('/orders/create')
            ->assertRedirect('/login');

    }
    

    /** @test */
    
    public function anAuthenticatedUserCanCreateAnOrder()
    {
        $product = $this->createProduct();

        Livewire::test(AddCartItem::class, ['product' => $product])
            ->call('addItem', $product);
        $this->actingAs(User::factory()->create())->get('/orders/create')->assertStatus(200);

        Livewire::test(CreateOrder::class)->assertSee(Cart::content()->first()->name);

    }
   

    /** @test */

    public function shoppingCartIsSavedInDatabaseWhenAUserLogsOut()
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

    public function shoppingCartReturnsWhenAUserLogsIn() 
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
    
    public function itDeletesTheShoppingCartWhenTheOrderIsCreatedAndRedirects()
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