<?php


namespace Enklare\Health;

class HealthCheck extends BasicHealthCheck implements \JsonSerializable {

    /**
     * version
     * system version we're checking
     * @var mixed
     */
    public $version;

    /**
     * checks
     * array of BasicHealthCheck objects, subchecks for the current check
     * @var array
     */
    public $checks = [];
    
    /**
     * __construct
     *
     * @param  mixed $service Service name to display in healthcheck
     * @param  mixed $version Service version to display in healthckeck
     * @return void
     */
    public function __construct(string $service, string $version = "0")
    {
        parent::__construct($service);
        $this->version = $version;
    }

    public function any(string $status)
    {
        foreach($this->checks as $c){
            if($c->status == $status) {
                return true;
            }
        }

        return false;
    }

    public function addCheck(BasicHealthCheck $check)
    {
        $this->checks[] = $check;

        // re-evaluate self status
        $this->evaluate();
    }

    public function evaluate()
    {
        if($this->any(self::WARN)) {
            $this->warning();
        }
        if($this->any(self::FAIL)) {
            $this->failed();
        }
    }

    public function jsonSerialize()
    {
        $item = parent::jsonSerialize();
        $item['version'] = $this->version;

        foreach($this->checks as $c) {
            $item['checks'][$c->service] = $c->jsonSerialize();
        }

        return $item;
    }
}
