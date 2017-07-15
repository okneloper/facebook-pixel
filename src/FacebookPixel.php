<?php

namespace Okneloper\FacebookPixel;

/**
 * Class FacebookPixel
 * @package Okneloper\FacebookPixel
 */
class FacebookPixel
{
    /**
     * Facebook Pixel ID
     * @var string|int
     */
    protected $id;

    protected $initData;

    /**
     * Pixel events to be rendered
     * @var Event[]
     */
    protected $events = [];

    public function __construct($id, $initData = [])
    {
        $this->id = $id;

        $this->initData = $initData;

        $this->addEvent(new StandardEvent('PageView'));
    }

    public function addEvent(Event $event)
    {
        $this->events[] = $event;
    }

    public function render()
    {
        $init = $this->fbq('init', (string)$this->id, $this->initData);

        $fbqs = [];
        $imgs = [];
        foreach ($this->events as $event) {
            $fbqs[] = $this->fbq('track', $event->getEventName(), $event->getParameters());
            $imgs[] = $this->img($event);
        }

        // fbq()'s
        $fbqs = implode("\n", $fbqs);

        // <img>'s
        $imgs = array_map(function ($src) {
            return '<img height="1" width="1" style="display:none" src="' . $src . '">';
        }, $imgs);

        $imgs = implode("\n", $imgs);

        return <<<FB_PIXEL
<!-- Facebook Pixel Code -->
<script>
    !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
    n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
    document,'script','https://connect.facebook.net/en_US/fbevents.js');
    $init
    $fbqs
</script>
<noscript>
    $imgs
</noscript>
<!-- End Facebook Pixel Code -->
FB_PIXEL;
    }

    /**
     * Returns array encoded as JSON
     * @param $array
     * @return null|string
     */
    protected function arrayToJson($array)
    {
        return $array ? json_encode($array, JSON_PRETTY_PRINT) : null;
    }

    /**
     * Renders an fbq() call with optional parameters
     * @param array ...$args
     * @return string
     */
    public function fbq(...$args)
    {
        if (empty($args)) {
            throw new \RuntimeException('Cannot call fbq() with no arguments');
        }

        // As per FB docs, 1st argument should be one of these
        $valid1stArg = [
            'init', 'track', 'trackCustom',
        ];

        // ensure it is and that args are passed separately and not as an array
        if (is_array($args[0]) || !in_array($args[0], $valid1stArg)) {
            $arg = print_r($args[0], true);
            throw new \RuntimeException(
                "argument 1 for fbq() should be either init, track, or trackCustom, [$arg] passed");
        }

        // discard null elements as optional from the end of args
        $last = count($args) - 1;
        while ($args[$last] === null || $args[$last] === []) {
            unset($args[$last]);
            $last--;
        }

        // encode arguments for output in JS
        $args = array_map(function ($arg) {
            if (is_string($arg)) {
                return "'$arg'";
            } elseif (is_array($arg)) {
                return $this->arrayToJson($arg);
            }
            return $arg;
        }, $args);

        return "fbq(" . implode(', ', $args) . ");";
    }

    /**
     * Returns URL of the noscript pixel
     * @param $event
     * @return string
     */
    protected function img(Event $event)
    {
        $query = [
            'id' => $this->id,
            'ev' => $event->getEventName(),
            'noscript' => 1,
        ] + $event->getParameters();
        $query = http_build_query($query);
        return "https://www.facebook.com/tr?$query";
    }
}
