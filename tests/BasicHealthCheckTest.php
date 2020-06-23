<?php

namespace Enklare\Health\Tests;

use Enklare\Health\BasicHealthCheck;
use Orchestra\Testbench\TestCase;

class BasicHealthCheckTest extends TestCase
{
  
    /** @test */
    public function pass_a_check(): void
    {
        $service_name = "my-service";
        $check = new BasicHealthCheck($service_name);

        $this->assertInstanceOf(BasicHealthCheck::class, $check);
        $this->assertEquals($service_name, $check->service);

        $check->passed();

        $this->assertEquals(BasicHealthCheck::PASS, $check->status());
    }
}
