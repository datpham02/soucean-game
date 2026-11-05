<?php

namespace Tests\Feature;

use Tests\TestCase;

class HealthCheckTest extends TestCase
{
    public function test_health_endpoint_returns_expected_shape(): void
    {
        $response = $this->getJson('/api/health');

        $response->assertOk();
        $response->assertJsonStructure([
            'app' => ['name', 'version', 'laravel', 'environment'],
            'database' => ['connection', 'reachable', 'message'],
            'reverb' => ['host', 'port', 'reachable', 'message'],
        ]);
        $response->assertJsonPath('database.reachable', true);
        $response->assertJsonPath('reverb.reachable', false);
    }
}
