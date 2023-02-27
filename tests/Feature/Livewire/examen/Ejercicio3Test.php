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


class Ejercicio3Test extends TestCase
{
    use  RefreshDatabase;


    /** @test */
    public function itShowsTheNewColumns()
    {
        Role::create(['name' => 'admin']);
        $admin = User::factory()->create()->assignRole('admin');
        $this->createProduct('producto1');


        $this->actingAs($admin)->get('/admin/dos')
            ->assertSee('producto1')
            ->assertSee('fecha de creación')
            ->assertSee('n vendidos')
            ->assertSee('stock')
            ->assertSee('Marca');
    }
     /** @test */
     public function subcategoryIsSeenUnderTheCategory()
     {
         Role::create(['name' => 'admin']);
         $admin = User::factory()->create()->assignRole('admin');
         
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
             'brand_id' => $brand->id,
             'quantity' => 2
         ]);
         Image::factory(2)->create([
             'imageable_id' => $product->id,
             'imageable_type' => Product::class
         ]);
 
         $this->actingAs($admin)->get('/admin/dos')
             ->assertSee($subcategory->name);
             ;
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
