<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class BasicSmokeTest extends TestCase
{

    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_frontPageLoadsTest()
    {
        $response = $this->get('/');
        $response->assertStatus(302);
    }

    public function test_registerPageLoadsTest()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
    }

    public function test_loginPageLoadsTest()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_ForgotPasswordLoadsTest(){
        $response = $this->get('/password/reset');
        $response->assertStatus(200);
    }
}