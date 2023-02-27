<?php

namespace Tests\Feature;

use Spatie\Permission\Models\Role;
use Tests\TestCase;
use App\Models\User;
use Tests\CreateData;
use Illuminate\Foundation\Testing\RefreshDatabase;


class Ejercicio1Test extends TestCase
{
    use  RefreshDatabase,CreateData;

      /** @test */
      public function itShowsTheProductsList()
      {
        Role::create(['name' => 'admin']);

        $admin = User::factory()->create()->assignRole('admin');
        $product= $this->createProduct();
  
        $this->actingAs($admin)->get('/admin/dos')
        ->assertStatus(200)
        ->assertSee($product->name);
          
      }
  
}