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

    /** @test */

    public function theButtonsAreVisible()   
    {
        $product = $this->createProduct();

        $this->browse(function (Browser $browser) use ($product) {
            $browser->visit('/products/' . $product->slug)
                ->assertVisible('@buttonLess')
                ->assertVisible('@buttonPlus')
                ->assertVisible('@cart');
        });
        
    }
    /** @test */

    public function productWithoutColorsHasPlusButtonEnabled() 
    {
        $product = $this->createProduct();
        $this->browse(function (Browser $browser) use ($product) {

            $browser->visit('/products/' . $product->slug)
                ->assertButtonEnabled('@buttonPlus');
        });
    }
       
    /** @test */

    public function productWithColorsHasPlusButtonDisabled() 
    {
        $product = $this->createProduct(true, false, 5);

        $this->browse(function (Browser $browser) use ($product) {

            $browser->visit('/products/' . $product->slug)
                ->assertButtonDisabled('@buttonPlus');
        });
       
    }
    /** @test */

    public function ProductWithColorsHasCartButtonVisibleAndEnable() 
    {
        $product = $this->createProduct(true, true, 5);

        $this->browse(function (Browser $browser) use ($product) {

            $browser->visit('/products/' . $product->slug)
                ->assertButtonDisabled('@buttonPlus');
        });
        
    }
    /** @test */

    public function ProductWithoutColorsHasCartButtonVisibleAndEnable()
    {
        $product = $this->createProduct();

        $this->browse(function (Browser $browser) use ($product) {

            $browser->visit('/products/' . $product->slug)
                ->assertButtonEnabled('@cart');
        });
        
    }
    /** @test */

    public function productWithColorsHasCartButtonDisabled() 
    {
        $product = $this->createProduct(5, true);

        $this->browse(function (Browser $browser) use ($product) {

            $browser->visit('/products/' . $product->slug)
                ->assertButtonDisabled('@cart');
        });
        
    }
    /** @test */
    public function productWithColorsAndSizHasCartButtonDisabled() 
    {
        $product = $this->createProduct(true, true, 5);

        $this->browse(function (Browser $browser) use ($product) {

            $browser->visit('/products/' . $product->slug)
                ->assertButtonDisabled('@cart');
        });
    }
        
    /** @test */

    public function plusButtonLimitIsProductQuantity() 
    {
        $product = $this->createProduct(false, false, 2);
        $quantity = $product->quantity;
        $this->browse(function (Browser $browser) use ($product, $quantity) {
            $browser->visit('/products/' . $product->slug)
                ->assertButtonEnabled('@buttonPlus');
            $browser->press('@buttonPlus');

            $browser->press('@buttonPlus')
                ->pause(500)
                ->assertButtonDisabled('@buttonPlus');
        });
        
    }
    /** @test */

    public function decrementButtonLimitIsZero()   
    {
        $product = $this->createProduct(false, false, 3);
        $quantity = $product->quantity;
        $this->browse(function (Browser $browser) use ($product, $quantity) {
            $browser->visit('/products/' . $product->slug);

            $browser->assertButtonDisabled('@buttonLess')
                ->press('@buttonPlus')
                ->pause(500)
                ->assertButtonEnabled('@buttonLess');
        });
    }
      
    /** @test */

    public function itemsWithoutColorHasNotColorSelectNeitherSizeSelect() 
    {
        $product = $this->createProduct();
        $this->browse(function (Browser $browser) use ($product) {
            $browser->visit('/products/' . $product->slug);
            $browser->assertMissing('@color')
                ->assertMissing('@size');
        });
        
    }
    /** @test */

    public function itemsWithColorAndWithoutSizeHasColorSelectButNotSizeSelect() 
    {
        $product = $this->createProduct(true, false, 5);
        $color = Color::create([
            'name' => 'blue',
        ]);
        $product->colors()->attach($color->id, ['quantity' => 1]);

        $this->browse(function (Browser $browser) use ($product, $color) {
            $browser->visit('/products/' . $product->slug);
            $browser->pause(500)
                ->assertVisible('@color')
                ->click('@color')
                ->pause(500)
                ->assertSelectHasOption('@color', $color->id)
                ->assertMissing('@size');
        });
        
    }
    /** @test */

    public function itemsWithColorAndSizeHasColorSelectAndSizeSelect()
    {


        $product = $this->createProduct(true, true, 5);
        $color = Color::create(['name' => 'green',]);
        $size = Size::factory()->create([
            'name' => 'talla M',
            'product_id' => $product->id,
        ]);
        $color->sizes()->attach($size->id, ['quantity' => 3]);
        $product->colors()->attach($color->id, ['quantity' => 2]);


        $this->browse(function (Browser $browser) use ($product, $color, $size) {
            $browser->visit('/products/' . $product->slug);
            $browser->pause(500)
                ->assertVisible('@color')
                ->assertVisible('@size')
                ->assertSelectHasOption('@size', $size->id)
                ->select('@size', $size->id)
                ->pause(1000)
                ->assertSelectHasOption('@color', $color->id)
                ->select('@color', $size->id);
        });
        
    }
}
