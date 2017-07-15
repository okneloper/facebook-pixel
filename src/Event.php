<?php

namespace Okneloper\FacebookPixel;

/**
 * Interface Event
 * @package Okneloper\FacebookPixel
 *
 * Example event call:
 * fbq('track', 'eventName', {customData});
 */
interface Event
{
    /**
     * Returns event name, e.g. 'eventName'
     * @return string
     */
    public function getEventName();

    /**
     * @todo add example
     * Returns associative array of custom data, e.g. [
     * ]
     * @return array
     */
    public function getParameters();
}
