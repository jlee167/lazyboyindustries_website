<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CommerceTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testPurcahse()
    {
        $user = User::where('username', '=', 'testuser100')
                    ->first();

        $response = $this->actingAs($user)
                        ->withSession(['banned' => false])
                        ->postJson('/product/order', [
                            'productID'     => 1,
                            'quantity'      => 1
                        ]);

        $response->assertStatus(200);
    }
}
