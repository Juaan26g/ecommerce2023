<?php

namespace Tests\Browser;

use App\Models\{Category, Product, Subcategory, Image, Brand, Color, Size};

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ProductsDetailsTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function test_product_details_can_be_seen()
    {
        $brand = Brand::factory()->create();

        $category = Category::factory()->create();
        $category->brands()->attach($brand->id);

        $subcategory = Subcategory::factory()->create([
            'category_id' => $category->id,
        ]);

        $product = Product::factory()->create([
            'subcategory_id' => $subcategory->id,
            'brand_id' => $brand->id
        ]);

        Image::factory()->create([
            'imageable_id' => $product->id,
            'imageable_type' => Product::class
        ]);

        $this->browse(function (Browser $browser) use ($category, $subcategory, $product) {
            $browser->visit('/categories/' . $category->slug)
                ->pause(500)
                ->clickLink($product->name)
                ->pause(500)
                ->assertPathIs('/products/' . $product->slug)
                ->assertSee(substr($product->name ,0 ,8))
                ->pause(500)
                ->assertSee($product->price)
                ->assertSee($product->quantity)
                ->pause(500)
                ->assertVisible('@buttonPlus')
                ->assertVisible('@buttonLess')
                ->assertVisible('@cart');
        });
    }

    /** @test */
    public function test_the_add_button()
    {
        $brand = Brand::factory()->create();

        $category = Category::factory()->create();
        $category->brands()->attach($brand->id);

        $subcategory = Subcategory::factory()->create([
            'category_id' => $category->id,
        ]);

        $product = Product::factory()->create([
            'subcategory_id' => $subcategory->id,
            'brand_id' => $brand->id
        ]);

        Image::factory()->create([
            'imageable_id' => $product->id,
            'imageable_type' => Product::class
        ]);

        $this->browse(function (Browser $browser) use ($product) {
            $browser->visit('/products/' . $product->slug)
                ->pause(500)
                ->assertSee($product->name);
            for ($i = 0; $i < $product->quantity; $i++) {
                $browser->press('@buttonPlus');
            };
            $browser->pause(500)
                ->assertButtonDisabled('@buttonPlus');
        });
    }

    /** @test */
    public function test_substract_button()
    {
        $brand = Brand::factory()->create();

        $category = Category::factory()->create();
        $category->brands()->attach($brand->id);

        $subcategory = Subcategory::factory()->create([
            'category_id' => $category->id,
        ]);

        $product = Product::factory()->create([
            'subcategory_id' => $subcategory->id,
            'brand_id' => $brand->id
        ]);

        Image::factory()->create([
            'imageable_id' => $product->id,
            'imageable_type' => Product::class
        ]);

        $this->browse(function (Browser $browser) use ($product) {
            $browser->visit('/products/' . $product->slug)
                ->pause(500)
                ->assertSee($product->name);
            for ($i = 0; $i < $product->quantity; $i++) {
                $browser->press('@buttonLess');
            };
            $browser->pause(500)
                ->assertButtonDisabled('@buttonLess');
        });
    }

    /** @test */
    public function test_color_and_size_can_be_seen()
    {
        $brand = Brand::factory()->create();

        $category = Category::factory()->create();
        $category->brands()->attach($brand->id);

        $subcategory = Subcategory::factory()->create([
            'category_id' => $category->id,
            'color' => true,
            'size' => true
        ]);

        $product = Product::factory()->create([
            'subcategory_id' => $subcategory->id,
            'brand_id' => $brand->id
        ]);

        Image::factory()->create([
            'imageable_id' => $product->id,
            'imageable_type' => Product::class
        ]);

        $this->browse(function (Browser $browser) use ($product) {
            $browser->visit('/products/' . $product->slug)
                ->pause(500)
                ->assertSee($product->name)
                ->pause(500)
                ->assertSee('@size')
                ->pause(500)
                ->assertSee('@color');
        });
    }
}
