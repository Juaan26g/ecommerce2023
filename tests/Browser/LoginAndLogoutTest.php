<?php

namespace Tests\Browser;


use App\Models\{Category, User};
use Carbon\Factory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LoginAndLogoutTest extends DuskTestCase
{
    use DatabaseMigrations;
    
    /** @test */
    public function loginAndRegisterAreSeenIfUnlogged()
    {
        Category::factory()->create();
        
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
            ->pause(500)
            ->click('@userIcon')
            ->pause(500)
            ->assertSee('Registrarse')
            ->assertSee('Iniciar sesión');
        });
    }

    /** @test */
    public function logoutAndProfileAreSeenIfLogged()
    {
        Category::factory()->create();
        $user = User::factory()->create();
        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/login')
            ->type('email', $user->email)
            ->type('password', 'password')
            ->pause(500)
            ->press('@login')
            ->pause(500)
            ->assertPathIs('/')
            ->click('@loggedUserIcon')
            ->pause(500)
            ->assertSee('Perfil')
            ->assertSee('Finalizar sesión');
        });
    }
}