<?php

namespace Tests\Feature;

use App\Providers\AppServiceProvider;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_https_app_url_is_used_for_generated_routes(): void
    {
        config()->set('app.url', 'https://luxury-enclosure-around.ngrok-free.dev');

        $provider = new AppServiceProvider($this->app);
        $provider->boot();

        $this->assertSame(
            'https://luxury-enclosure-around.ngrok-free.dev/login',
            url('/login')
        );
    }
}
