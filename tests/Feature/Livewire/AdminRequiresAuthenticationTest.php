<?php

namespace Tests\Feature\LivewireTest;


use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\CreateData;
use Tests\TestCase;
use App\Http\Livewire\AddCartItem;
use App\Http\Livewire\CreateOrder;


class AdminRequiresAuthenticationTest extends TestCase
{
    use RefreshDatabase, CreateData;


    /** @test */
    public function notLoggedUserCantAccessAdminRoutes() 
    {
        $this->get('/admin')->assertStatus(302)->assertRedirect('/login');
    }
   
    /** @test */
    public function OnlyAdminUsersCanAccessAdmin() 
    {
        $role = Role::create(['name' => 'admin']);

        $admin = User::factory()->create()->assignRole('admin');
        $endUser = User::factory()->create();

        $this->actingAs($admin)->get('/admin')->assertStatus(200);
        $this->actingAs($endUser)->get('/admin')->assertStatus(403);
    }
   
    /** @test */
    public function anOrderInOnlyAccessibleByOneUser() 
    {

        $this->actingAs(User::factory()->create());

        $product = $this->createProduct();

        Livewire::test(AddCartItem::class, ['product' => $product])
            ->call('addItem', $product);


        Livewire::test(CreateOrder::class)
            ->set('contact', 'contact')
            ->set('phone', '61452111')
            ->call('create_order');

        $this->actingAs(User::factory()->create(['id' => 4]))->get('/orders/1/payment')->assertStatus(403);
    }
    
}
