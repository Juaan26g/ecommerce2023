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


class Ejercicio6Test extends TestCase
{
    use  RefreshDatabase;

/** @test */
public function filtersByPrice()
{
    
    $this->createProduct('Microfono');
    $category = Category::factory()->create(['name' => 'ala']);
    $subcategory = Subcategory::factory()->create([
        'category_id' => $category->id,
        'color' => false,
        'size' => false,
    ]);

    $brand = Brand::factory()->create(['name' => 'dos']);
    $category->brands()->attach([$brand->id]);

    $product = Product::factory()->create([
        'subcategory_id' => $subcategory->id,
        'name' => 'aleatorio',
        'price'=> 20,
        'brand_id' => $brand->id,
        'quantity' => 2
    ]);
   
    Image::factory(2)->create([
        'imageable_id' => $product->id,
        'imageable_type' => Product::class
    ]);
    
   
    Livewire::test(ShowProducts2::class)
    ->set('price', '20')
        ->assertSee('ala')
        ->assertDontSee('Microfono');
}
    /** @test */
    public function filtersByBrand()
    {
        
        $this->createProduct('Microfono');
        $category = Category::factory()->create(['name' => 'ala']);
        $subcategory = Subcategory::factory()->create([
            'category_id' => $category->id,
            'color' => false,
            'size' => false,
        ]);

        $brand = Brand::factory()->create(['name' => 'dos']);
        $category->brands()->attach([$brand->id]);

        $product = Product::factory()->create([
            'subcategory_id' => $subcategory->id,
            'name' => 'aleatorio',
            'brand_id' => $brand->id,
            'quantity' => 2
        ]);
       
        Image::factory(2)->create([
            'imageable_id' => $product->id,
            'imageable_type' => Product::class
        ]);
        
       
        Livewire::test(ShowProducts2::class)
        ->set('brand', 'dos')
            ->assertSee('aleatorio')
            ->assertDontSee('Microfono');
    }

    /** @test */
    public function filtersByCategory()
    {
        
        $this->createProduct('Microfono');
        $category = Category::factory()->create(['name' => 'ala']);
        $subcategory = Subcategory::factory()->create([
            'category_id' => $category->id,
            'color' => false,
            'size' => false,
        ]);

        $brand = Brand::factory()->create(['name' => 'dos']);
        $category->brands()->attach([$brand->id]);

        $product = Product::factory()->create([
            'subcategory_id' => $subcategory->id,
            'name' => 'aleatorio',
            'brand_id' => $brand->id,
            'quantity' => 2
        ]);
       
        Image::factory(2)->create([
            'imageable_id' => $product->id,
            'imageable_type' => Product::class
        ]);
        
       
        Livewire::test(ShowProducts2::class)
        ->set('category', 'oto')
            ->assertSee('Microfono')
            ->assertDontSee('aleatorio');
    }

        /** @test */
    public function filtersByName()
    {
        $this->createProduct('Microfono');
        $this->createProduct('Estetoscopio');

        Livewire::test(ShowProducts2::class)
            ->set('search', 'Mic')
            ->assertSee('Microfono')
            ->assertDontSee('Estetoscopio');
    }
    


    private function createProduct($name)
    {
        $category = Category::factory()->create(['name' => 'oto']);
        $subcategory = Subcategory::factory()->create([
            'category_id' => $category->id,
            'color' => false,
            'size' => false,
        ]);

        $brand = Brand::factory()->create(['name' => 'marca']);
        $category->brands()->attach([$brand->id]);

        $product = Product::factory()->create([
            'subcategory_id' => $subcategory->id,
            'name' => $name,
            'price' => 50,
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
