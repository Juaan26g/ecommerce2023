<?php

namespace Tests\Browser;

use App\Models\{Category, Product, Subcategory, Image, Brand};
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class FiveProductsAreSeenTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function atLeastFiveProductsAreSeenInTheMainView()
    {
        $category  = Category::factory()->create([
            'name' => 'Consola y videojuegos',
        ]);

        $brand = Brand::factory()->create();
        $category->brands()->attach($brand->id);

        $subcategory = Subcategory::factory()->create([
            'category_id' => $category->id,
            'name' => 'Xbox'
        ]);

        Product::factory(5)->create([
            'subcategory_id' => $subcategory->id,
            
        ]);
        $products = Product::where('subcategory_id', $subcategory->id)->get();
        
        foreach ($products as $product) {

            Image::factory()->create([
                'imageable_id' => $product->id,
                'imageable_type' => Product::class
            ]);
        }
       
        $this->browse(function (Browser $browser) use ($products){
            $browser->visit('/')
            ->pause(500);
            foreach ($products as $product) {
                $browser->assertSee($product->name);
            }
            
            
        
    });
    }



    /** @test */
    public function publishedProductsAreSeenInTheMainView()
    {
        $category  = Category::factory()->create([
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

        Product::factory(2)->create([
            'subcategory_id' => $subcategory->id,
            'status' => 1
            
        ]);
        $products = Product::where('subcategory_id', $subcategory->id)->get();
        foreach ($products as $product) {

            Image::factory()->create([
                'imageable_id' => $product->id,
                'imageable_type' => Product::class
            ]);
        }
       
        $this->browse(function (Browser $browser) use ($products) {
            $browser->visit('/')
                    ->pause(500);
        
            $productsToSee = collect($products)->take(3);
            $productsToDontSee = collect($products)->slice(3);
        
            foreach ($productsToSee as $product) {
                $browser->assertSee($product->name);
            }
        
            foreach ($productsToDontSee as $product) {
                $browser->assertDontSee($product->name);
            }
        });
    }
}
