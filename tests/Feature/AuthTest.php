<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testLogin()
    {
        $response = $this->postJson('/auth', ['testuser100' => 'password']);
        $response->assertStatus(200);
    }

    public function testKakaoLogin()
    {
        /* Todo */
    }

    public function testGoogleLogin()
    {
        /* Todo */
    }
}
