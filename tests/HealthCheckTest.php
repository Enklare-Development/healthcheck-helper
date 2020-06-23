<?php

namespace Enklare\Health\Tests;

use Enklare\Health\HealthCheck;
use Orchestra\Testbench\TestCase;

class HealthCheckTest extends TestCase
{
  
    /** @test */
    public function pass_a_check(): void
    {
        $service_name = "my-service";
        $service_version = "1.0.5";
        $check = new HealthCheck($service_name, $service_version);

        $this->assertInstanceOf(HealthCheck::class, $check);
        $this->assertEquals($service_name, $check->service);

        $check->passed();

        $this->assertEquals(HealthCheck::PASS, $check->status());
    }
}
