<?php

namespace Tests\Browser;

use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CategoriesAndSubcategoriesTest extends DuskTestCase
{
    
    /**@test*/
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
}
