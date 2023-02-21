<?php

namespace Tests\Feature;

use App\Http\Livewire\Search;
use App\Models\{Subcategory,Brand,Category,Image,Product};
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;


class SearchTest extends TestCase
{
    use RefreshDatabase;

    /** @test */

    public function filtersByName()
    {
        $this->createProduct('Microfono');
        $this->createProduct('Estetoscopio');

        Livewire::test(Search::class)
            ->set('search', 'Mic')
            ->assertSee('Microfono')
            ->assertDontSee('Estetoscopio');
    }
    /** @test */
    public function ItDoesntShowAnyProductIfSearchInputIsEmpty()
    {
        $this->createProduct('Microfono');
        $this->get('/');

        Livewire::test(Search::class)
            ->set('search', '')
            ->assertDontSee('Microfono');
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
