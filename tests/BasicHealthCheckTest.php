<?php

namespace Enklare\Health\Tests;

use Enklare\Health\BasicHealthCheck;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class BasicHealthCheckTest extends TestCase
{

      
    /** @test */
    public function checkCreated(): void
    {
        $service_name = "my-service";
        $check = new BasicHealthCheck($service_name);

        $this->assertInstanceOf(BasicHealthCheck::class, $check);
        $this->assertEquals($service_name, $check->service);
    }
  
    /** @test */
    public function checkGetsPass(): void
    {
        $service_name = "my-service";
        $check = new BasicHealthCheck($service_name);

        $check->passed();

        $this->assertEquals(BasicHealthCheck::PASS, $check->status());
    }

    /** @test */
    public function checkGetsWarning(): void
    {
        $service_name = "my-service";
        $check = new BasicHealthCheck($service_name);

        $check->warning();

        $this->assertEquals(BasicHealthCheck::WARN, $check->status());
    }

    /** @test */
    public function checkGetsFailed(): void
    {
        $service_name = "my-service";
        $check = new BasicHealthCheck($service_name);

        $check->failed();

        $this->assertEquals(BasicHealthCheck::FAIL, $check->status());
    }

    /**
     * @test
     */
    public function timestampsTest(): void
    {
        $service_name = "my-service";
        $check = new BasicHealthCheck($service_name);

        $this->assertNotNull($check->executionStartTime(), 'BasicHealthCheck had no start timestamp');
        $this->assertNull($check->executionStopTime(), 'BasicHealthCheck had stop-timestamp too early');

        $check->passed();

        $this->assertNotNull($check->executionStopTime(), 'BasicHealthCheck had no end timestamp');
    }
    
    /**
     * @test
     */
    public function manualTimestamps(): void
    {
        $service_name = "my-service";
        $check = new BasicHealthCheck($service_name, false);

        $this->assertNull($check->executionStartTime(), 'BasicHealthCheck had start timestamp when should not');
        $this->assertNull($check->executionStopTime(), 'BasicHealthCheck had end timestamp when should not');

        $check->measureStart();
        $this->assertInstanceOf(Carbon::class, $check->executionStartTime(), 'BasicHealthCheck start time is not a carbon date');

        $check->measureStop();
        $this->assertInstanceOf(Carbon::class, $check->executionStopTime(), 'BasicHealthCheck end time is not a carbon date');

        $this->assertNotNull($check->executionStartTime(), 'BasicHealthCheck had no start timestamp');
        $this->assertNotNull($check->executionStopTime(), 'BasicHealthCheck had no end timestamp');
    }
}
