<?php

namespace Tests\Feature;

use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SanctumTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        $response = $this->post('/sanctum/token');

        $response->assertStatus(200);
    }

    public function sanctum()
    {
        Sanctum::actingAs(
            factory(User::class)->make(),
            ['*']
        );
    }
}
