<?php

namespace Tests\Browser;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CategoriesAndSubcategoriesTest extends DuskTestCase
{
    use DatabaseMigrations;
    
    /** @test */
    public function categoriesAreSeenWhenCategoriesButtonIsClicked()
    {
        Category::factory()->create([
            'name' => 'Consola y videojuegos',
        ]);


        $this->browse(function (Browser $browser) {
            $browser->visit('/')
            ->pause(500)
            ->clickLink('Categorías')
            ->assertSee('Consola y videojuegos');
        });
    }

    /** @test */
    public function subcategoriesAreSeenWhenCategoriesButtonIsClicked()
    {
        $category = Category::factory()->create([
            'name' => 'Consola y videojuegos',
        ]);

        $category2 = Category::factory()->create([
            'name' => 'TV, audio y video',
        ]);

        Subcategory::factory()->create([
            'name' => 'Videojuegos para PC',
            'category_id' => $category->id
        ]);

        Subcategory::factory()->create([
            'name' => 'TV y audio',
            'category_id' => $category2->id

        ]);


        $this->browse(function (Browser $browser) {
            $browser->visit('/')
            ->pause(500)
            ->clickLink('Categorías')
            ->pause(500)
            ->assertSee('Consola y videojuegos')
            ->mouseover('@categoryNav')
            ->pause(500)
            ->assertSee('Videojuegos para PC')
            ->assertDontSee('TV y audio');
        });
    }
}
