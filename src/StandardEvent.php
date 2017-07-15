<?php

namespace Okneloper\FacebookPixel;

/**
 * Class Event
 * @package Okneloper\FacebookPixel
 */
class StandardEvent implements Event
{
    /**
     * Event name
     * @var string
     */
    protected $eventName;

    /**
     * Event parameters
     * @var array
     */
    protected $parameters;

    /**
     * StandardEvent constructor.
     * @param string $eventName
     * @param array $parameters
     */
    public function __construct($eventName, array $parameters = [])
    {
        $this->eventName = $eventName;
        $this->parameters = $parameters;
    }

    /**
     * Returns event name, e.g. 'eventName'
     * @return string
     */
    public function getEventName()
    {
        return $this->eventName;
    }

    /**
     * Returns associative array of custom data, e.g. [
     * ]
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }
}
