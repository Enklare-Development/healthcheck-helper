<?php

namespace Enklare\Health;

use Carbon\Carbon;

class BasicHealthCheck implements \JsonSerializable {

    /**
     * Constant status values
     */
    const FAIL = "fail";
    const WARN = "warn";
    const PASS = "pass";
    
    /**
     * _executionStart timestamp
     *
     * @var int
     */
    private $_executionStart;
    
    /**
     * _executionStop timestamp
     *
     * @var int
     */
    private $_executionStop;

    /**
     * status
     *
     * @var string Status constant
     */
    protected $_status;
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
        $this->measureStop();
        $this->_status = self::FAIL;
        return $this;
    }

    /**
     * warning
     * Set warning status and return self for chainability
     *
     * @return BasicHealthCheck
     */
    public function warning()
    {
        $this->measureStop();
        $this->_status = self::WARN;
        return $this;
    }

    /**
     * passed
     * Set passed status and return self for chainability
     *
     * @return BasicHealthCheck
     */
    public function passed()
    {
        $this->measureStop();
        $this->_status = self::PASS;
        return $this;
    }

    /**
     * status
     * get the current status of this subcheck
     * @return string
     */
    public function status()
    {
        return $this->_status;
    }

    /**
     * __construct
     *
     * @param  mixed $service
     * @return void
     */
    public function __construct(string $service, $autostart = true)
    {
        $this->service = $service;
        if($autostart) {
            $this->measureStart();
        }
    }

    /**
     * start execution time measurement
     *
     * @return void
     * @OA\Property()
     **/
    public function measureStart()
    {
        $this->_executionStart = new Carbon();
    }

    /**
     * stop execution time measurement
     *
     * @return void
     * @OA\Property()
     **/
    public function measureStop()
    {
        $this->_executionStop = new Carbon();
    }

    /**
     * get check time measurement in milliseconds
     *
     * @return int
     * @OA\Property()
     **/
    public function executionTimeMs()
    {
        if($this->_executionStart && $this->_executionStop) {
            return round($this->_executionStart->diffInMilliseconds($this->_executionStop));
        }
        return null;
    }

    /**
     * Get check time measurement starttime as Carbon instance
     *
     * @return Carbon
     * @OA\Property()
     **/
    public function executionStartTime()
    {
        return $this->_executionStart ?: null;
    }

    /**
     * Get check time measurement starttime as Carbon instance
     *
     * @return Carbon
     * @OA\Property()
     **/
    public function executionStopTime()
    {
        return $this->_executionStop ?: null;
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
        return [
            'status'  => $this->_status,
            'service' => $this->service,
            'executionTimeMs' => $this->executionTimeMs(),
            'executionStartTime' => $this->executionStartTime(),
            'executionStopTime' => $this->executionStopTime(),
        ];
    }
}
