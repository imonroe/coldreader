<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Laravel\Dusk\Chrome;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\User;

class BrowserSmokeTest extends DuskTestCase
{

    use DatabaseMigrations;

    /**
     * A basic browser test example.
     *
     * @return void
     */

    public function testRegistration()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                ->type('#name', 'Test User')
                ->type('#email', 'test@here.com')
                ->type('#password', 'secret')
                ->type('#password-confirm', 'secret')
                ->press('Register')
                ->assertPathIs('/home');
        });

        $this->browse(function(Browser $browser){
            $browser->visit('/register')
                ->assertPathIs('/home');
        });

        $this->browse(function(Browser $browser){
            $browser->visit('/logout#')
                ->assertPathIs('/logout#');
        });

        $this->browse(function(Browser $browser){
            $browser->visit('/register')
                ->assertSee('Already Registered.');
        });
    }

    public function testLogin()
    {
        $user = factory(User::class)->create([
            'email' => 'test_user1@test.com',
        ]);
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                    ->type('email', $user->email)
                    ->type('password', 'secret')
                    ->press('Login')
                    ->assertPathIs('/home');
        });
    }
}
