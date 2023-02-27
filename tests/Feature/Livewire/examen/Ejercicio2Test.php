<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Image;
use App\Models\Product;
use App\Models\Subcategory;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;
use App\Models\User;
use App\Http\Livewire\Admin\ShowProducts2;
use Illuminate\Foundation\Testing\RefreshDatabase;


class Ejercicio2Test extends TestCase
{
    use  RefreshDatabase;


    /** @test */
    public function itPaginates()
    {
        Role::create(['name' => 'admin']);
        $admin = User::factory()->create()->assignRole('admin');
        $this->createProduct('producto1');
        $this->createProduct('producto2');
        $this->createProduct('producto3');
        $this->createProduct('producto4');
        $this->createProduct('producto5');
        $this->createProduct('producto6');

        $this->actingAs($admin)->get('/admin/dos')
            ->assertSee('producto1')
            ->assertSee('producto2')
            ->assertSee('producto3')
            ->assertSee('producto4')
            ->assertSee('producto5')
            ->assertSee('producto6');

        Livewire::test(ShowProducts2::class)

            ->set('pagination', 5)
            ->assertSee('producto1')
            ->assertSee('producto2')
            ->assertSee('producto3')
            ->assertSee('producto4')
            ->assertSee('producto5')
            ->assertDontSee('producto6');
    }
    private function createProduct($name)
    {
        $category = Category::factory()->create();
        $subcategory = Subcategory::factory()->create([
            'category_id' => $category->id,
            'color' => false,
            'size' => false,
        ]);

        $brand = Brand::factory()->create();
        $category->brands()->attach([$brand->id]);

        $product = Product::factory()->create([
            'subcategory_id' => $subcategory->id,
            'name' => $name,
            'brand_id' => $brand->id,
            'quantity' => 2
        ]);
        Image::factory(2)->create([
            'imageable_id' => $product->id,
            'imageable_type' => Product::class
        ]);
        return $product;
    }
}
