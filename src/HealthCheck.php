<?php

namespace Enklare\Health;

class HealthCheck extends BasicHealthCheck implements \JsonSerializable
{

    /**
     * System version we're checking
     * 
     * @var mixed
     * @OA\Property()
     */
    public $version;

    /**
     * Array of BasicHealthCheck objects, subchecks for the current check
     * 
     * @var array
     * @OA\Property()
     */
    private $_checks = [];
    
    /**
     * __construct
     *
     * @param mixed $service Service name to display in healthcheck
     * @param mixed $version Service version to display in healthckeck
     * 
     * @return void
     */
    public function __construct(string $service, string $version = "0")
    {
        parent::__construct($service);
        $this->version = $version;
    }
    
    /**
     * Find out if any subcheck matches this status
     *
     * @param string $status The status to match against
     * 
     * @return boolean
     */
    private function _anyCheckMatchesStatus(string $status): bool
    {
        foreach ($this->_checks as $c) {
            if ($c->status() == $status) {
                return true;
            }
        }

        return false;
    }
    
    /**
     * Add a subcheck for this service
     *
     * @param BasicHealthCHeck $check
     * 
     * @return void
     */
    public function addCheck(BasicHealthCheck $check): void
    {
        $this->_checks[] = $check;

        // re-evaluate self status
        $this->evaluate();
    }
    
    /**
     * Evaluates this tests health by checking if any subchecks failed
     *
     * @return void
     */
    public function evaluate(): void
    {
        if ($this->_anyCheckMatchesStatus(self::WARN)) {
            $this->warning();
        }
        if ($this->_anyCheckMatchesStatus(self::FAIL)) {
            $this->failed();
        }
    }
    
    /**
     * JSON serialization method (native serialize support)
     *
     * Returns a serializable object from this instance
     *
     * @return array
     **/
    public function jsonSerialize()
    {
        $item = parent::jsonSerialize();
        $item['version'] = $this->version;

        foreach ($this->_checks as $c) {
            $item['checks'][$c->service] = $c->jsonSerialize();
        }

        return $item;
    }
}
