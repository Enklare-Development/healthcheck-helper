<?php

namespace Enklare\Health;

class BasicHealthCheck implements \JsonSerializable {

    /**
     * Constant status values
     */
    const FAIL = "fail";
    const WARN = "warn";
    const PASS = "pass";


    /**
     * status
     *
     * @var string Status constant
     */
    protected $status;
    /**
     * service
     *
     * @var string Service name
     */
    public $service;

    /**
     * failed
     * Set failed status and return self for chainability
     *
     * @return BasicHealthCheck
     */
    public function failed()
    {
        return $this->status = self::FAIL;
    }

    /**
     * warning
     * Set warning status and return self for chainability
     *
     * @return BasicHealthCheck
     */
    public function warning()
    {
        return $this->status = self::WARN;
    }

    /**
     * passed
     * Set passed status and return self for chainability
     *
     * @return BasicHealthCheck
     */
    public function passed()
    {
        return $this->status = self::PASS;
    }

    /**
     * status
     * get the current status of this subcheck
     * @return string
     */
    public function status(){
        return $this->status;
    }

    /**
     * __construct
     *
     * @param  mixed $service
     * @return void
     */
    public function __construct(string $service)
    {
        $this->service = $service;
    }

    /**
     * jsonSerialize
     * Returns a serializable object from this instance
     * @return mixed
     */
    public function jsonSerialize()
    {
        return [
            'status'  => $this->status,
            'service' => $this->service
        ];
    }
}
