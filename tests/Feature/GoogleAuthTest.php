<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GoogleAuthTest extends TestCase
{
    public function test_google_login_route_redirects(): void
    {
        $response = $this->get(route('google.login'));
        // Socialite redirects to google oauth URL (302)
        $response->assertStatus(302);
        $this->assertStringContainsString('accounts.google.com', $response->headers->get('Location'));
    }

    public function test_google_callback_route_exists(): void
    {
        // When accessed without oauth code, callback gracefully redirects to login with error
        $response = $this->get(route('google.callback'));
        $response->assertRedirect(route('login'));
    }
}
