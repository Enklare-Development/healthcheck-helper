<?php

namespace Enklare\Health\Tests;

use Enklare\Health\HealthCheck;
use Enklare\Health\BasicHealthCheck;;
use PHPUnit\Framework\TestCase;

class HealthCheckTest extends TestCase
{
  
    /** @test */
    public function checkCreated(): void
    {
        $service_name = "my-service";
        $service_version = "1.0.5";
        $check = new HealthCheck($service_name, $service_version);

        $this->assertInstanceOf(HealthCheck::class, $check);
        $this->assertEquals($service_name, $check->service);
        $this->assertEquals($service_version, $check->version);

        $check->passed();

        $this->assertEquals(HealthCheck::PASS, $check->status(), 'Check does not pass when being set to passed');
    }

    
    /** @test */
    public function subCheckWarningTests(): void
    {
        $service_name = "my-service";
        $service_version = "1.0.5";
        $check = new HealthCheck($service_name, $service_version);

        $check->passed(); // try and change my mind

        $passingSubcheck = new BasicHealthCheck('subcheck-pass');
        $passingSubcheck->passed();

        $check->addCheck($passingSubcheck);
        $this->assertEquals(HealthCheck::PASS, $check->status(), 'Check does not pass when subchecks pass');

        $warningSubcheck = new BasicHealthCheck('subcheck-warn');
        $warningSubcheck->warning();

        $check->addCheck($warningSubcheck);
        $this->assertEquals(HealthCheck::WARN, $check->status(), 'Check does not warn when subcheck warn');

        $failingSubcheck = new BasicHealthCheck('subcheck-fail');
        $failingSubcheck->failed();

        $check->addCheck($failingSubcheck);
        $this->assertEquals(HealthCheck::FAIL, $check->status(), 'Check does not fail when any subcheck fails');
    }

    /** @test */
    public function subCheckFailureTests(): void
    {
        $service_name = "my-service";
        $service_version = "1.0.5";
        $check = new HealthCheck($service_name, $service_version);

        $check->passed(); // try and change my mind

        $warningSubcheck = new BasicHealthCheck('subcheck-warn');
        $warningSubcheck->warning();

        $check->addCheck($warningSubcheck);
        $this->assertEquals(HealthCheck::WARN, $check->status(), 'Check does not warn when subchecks are warning');

        $failingSubcheck = new BasicHealthCheck('subcheck-fail');
        $failingSubcheck->failed();

        $check->addCheck($failingSubcheck);
        $this->assertEquals(HealthCheck::FAIL, $check->status(), 'Check does not fail when all subchecks are failing');
    }
}