<?php

namespace Tests\Browser;

use App\Models\{Category, Product, Subcategory, Image, Brand, Color, Size};

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\CreateData;
use Tests\DuskTestCase;


class ProductsDetailsTest extends DuskTestCase
{
    use DatabaseMigrations, CreateData;
 
/** @test */
    
    public function ProductDetailsCanBeAccesedAndShowsAllHisStuff()    
    {
        $product = $this->createProduct();

        $this->browse(function (Browser $browser) use ($product) {
            $browser->visit('/products/' . $product->slug)
            ->pause(500)
                ->assertSee($product->name)
                ->assertSee($product->description)
                ->assertSee($product->price)
                ->assertSee($product->quantity);
        });
        
    }
   
}
