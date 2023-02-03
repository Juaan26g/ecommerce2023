<?php

namespace Tests\Browser;

use App\Models\{Category, Product, Subcategory, Image, Brand};
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CategoryDetailsTest extends DuskTestCase
{
    use DatabaseMigrations;

     /** @test */
     public function categoryDetailsCanBeAccessed()
     {
         $category = Category::factory()->create([
             'name' => 'Consola y videojuegos',
         ]);

         $brand = Brand::factory()->create();
        $category->brands()->attach($brand->id);

        $subcategory = Subcategory::factory()->create([
            'category_id' => $category->id,
            'name' => 'Xbox'
        ]);

        Product::factory(3)->create([
            'subcategory_id' => $subcategory->id,
            
        ]);
        $products = Product::where('subcategory_id', $subcategory->id)->get();
        foreach ($products as $product) {
            Image::factory()->create([
                'imageable_id' => $product->id,
                'imageable_type' => Product::class
            ]);
        }

         $this->browse(function (Browser $browser) use ($category,$subcategory,$brand,$products) {
             $browser->visit('/')
             ->pause(500)
             ->clickLink('Categorías')
             ->ClickLink('Consola y videojuegos')
             ->assertPathIs('/categories/' . $category->slug)
             ->pause(500)
             ->assertSee(strtoupper($category->name))
             ->pause(500)
             ->assertSeeLink($brand->name)
             ->assertSeeLink($subcategory->name);

             foreach ($products as $product) {
                $browser->assertSee($product->name);
            }
         });
     }
}