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

        $this->browse(function (Browser $browser) use ($category, $subcategory, $brand, $products) {
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
    /** @test */
    public function categoriesSidebarSubcategoriesFiltersWorks()
    {
        $brand = Brand::factory()->create([
            'name' => 'cum laude',
        ]);

        $category = Category::factory()->create();
        $category->brands()->attach($brand->id);

        $subcategory1 = Subcategory::factory()->create([
            'category_id' => $category->id,
            'name' => 'subcat',
        ]);

        $subcategory2 = Subcategory::factory()->create([
            'category_id' => $category->id,
            'name' => 'subcat2',
        ]);

        $product1 = Product::factory()->create([
            'subcategory_id' => $subcategory1->id,
            'name' => 'prod1',
        ]);


        Image::factory()->create([
            'imageable_id' => $product1->id,
            'imageable_type' => Product::class
        ]);

        $product2 = Product::factory()->create([
            'subcategory_id' => $subcategory2->id,
            'name' => 'prod2',
        ]);

        Image::factory()->create([
            'imageable_id' => $product2->id,
            'imageable_type' => Product::class
        ]);

        $this->browse(function (Browser $browser) use ($category, $subcategory1, $product1, $subcategory2, $product2) {
            $browser->visit('/categories/' . $category->slug)
                ->pause(500)
                ->assertSeeLink($subcategory1->name)
                ->assertSeeLink($subcategory2->name)
                ->clickLink($subcategory1->name)
                ->pause(1000)
                ->assertSeeLink(substr($product1->name, 0, 8))
                ->assertDontSeeLink(substr($product2->name, 0, 8));
        });
    }

    /** @test */

public function categoriesSidebarBrandsFiltersWorks()
{
    $brand1 = Brand::factory()->create( [
        'name' => 'cum laude'
    ]);
    $brand2 = Brand::factory()->create([
        'name' => 'suma cum laude'
    ]);

    $category = Category::factory()->create();
    $category->brands()->attach($brand1->id);
    $category->brands()->attach($brand2->id);

    $subcategory = Subcategory::factory()->create([
        'category_id' => $category->id,
        'name' => 'exsubcat'
    ]);

    $product1 = Product::factory()->create([
        'subcategory_id' => $subcategory->id,
        'brand_id' => $brand1->id,
        'name' => 'prod1'
    ]);

    Image::factory()->create([
        'imageable_id' => $product1->id,
        'imageable_type' => Product::class
    ]);

    $product2 = Product::factory()->create([
        'subcategory_id' => $subcategory->id,
        'brand_id' => $brand2->id,
        'name' => 'prod2'
    ]);

    Image::factory()->create([
        'imageable_id' => $product2->id,
        'imageable_type' => Product::class
    ]);

    $this->browse(function (Browser $browser) use ($category, $brand1, $brand2, $product1, $product2) {
        $browser->visit('/categories/'. $category->slug)
        ->pause(500)
        ->assertSeeLink($brand1->name)
        ->assertSeeLink($brand2->name)       
        ->clickLink($brand1->name)
        ->pause(500)
        ->assertSeeLink(substr($product1->name, 0, 8))
        ->assertDontSeeLink(substr($product2->name, 0, 8));
        });
}
}
