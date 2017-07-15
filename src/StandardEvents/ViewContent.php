<?php

namespace Okneloper\FacebookPixel\StandardEvents;

use Okneloper\FacebookPixel\StandardEvent;

/**
 * Class ViewContent
 * @package Okneloper\FacebookPixel\StandardEvents
 */
class ViewContent extends StandardEvent
{
    public function __construct($parameters = [])
    {
        parent::__construct('ViewContent', $parameters);
    }
}
