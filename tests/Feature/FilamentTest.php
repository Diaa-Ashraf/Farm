<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FilamentTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test Filament admin access.
     */
    public function test_admin_access(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/admin');

        $response->assertRedirect('/admin/cycles');
    }
}
