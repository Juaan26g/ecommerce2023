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
       /* $Product1 = $products[0];
        $Product2 = $products[1];
        $Product3 = $products[2];
        $Product4 = $products[3];
        $Product5 = $products[4]; */
        /* use($Product1,$Product2,$Product3,$Product4,$Product5)*/
        $this->browse(function (Browser $browser) use ($products){
            $browser->visit('/')
            ->pause(500);
            foreach ($products as $product) {
                $browser->assertSee($product->name);
            }
            /*->assertSee($Product1->name)
            ->assertSee($Product2->name)
            ->assertSee($Product3->name)
            ->assertSee($Product4->name)
            ->assertSee($Product5->name)*/
            
        
    });
    }
}
