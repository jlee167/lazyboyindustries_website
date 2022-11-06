<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testLoginNoOauth()
    {
        $response = $this->postJson('/auth', ['testuser100' => 'password']);

        $response->assertStatus(200);
    }
}
