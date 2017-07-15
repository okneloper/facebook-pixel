<?php

/**
 * Class RenderTest
 */
class RenderTest extends PHPUnit_Framework_TestCase
{
    public function testRendersInit()
    {
        $pixel = $this->newPixel();

        $script = $pixel->render();
        $this->assertContains("fbq('init', '12345');", $script);
        $this->assertContains("<img ", $script);
        $this->assertContains('src="https://www.facebook.com/tr?id=12345&ev=PageView&noscript=1"', $script);
    }

    public function testRendersInitWithCustomData()
    {
        $pixel = new \Okneloper\FacebookPixel\FacebookPixel(12345, [
            'em' => 'test@example.com',
        ]);

        $script = $pixel->render();
        $this->assertContains("fbq('init', '12345'", $script);
        $this->assertContains('"em": "test@example.com"', $script);
    }

    public function testRendersPageViewByDefault()
    {
        $pixel = $this->newPixel();

        $this->assertContains("fbq('track', 'PageView');", $pixel->render());
        $this->assertContains('src="https://www.facebook.com/tr?id=12345&ev=PageView&noscript=1"', $pixel->render());
    }

    public function testRendersAdditionalEvents()
    {
        $pixel = $this->newPixel();

        $pixel->addEvent(new \Okneloper\FacebookPixel\StandardEvents\ViewContent());

        $this->assertContains("fbq('track', 'ViewContent');", $pixel->render());
        $this->assertContains('src="https://www.facebook.com/tr?id=12345&ev=ViewContent&noscript=1"', $pixel->render());
    }

    public function testFbqDoesNotAcceptNoArgs()
    {
        $pixel = $this->newPixel();

        $this->expectException(RuntimeException::class);

        $pixel->fbq();
    }

    public function testFbqOnlyAcceptsStringAs1stArg()
    {
        $pixel = $this->newPixel();

        $this->expectException(RuntimeException::class);

        $pixel->fbq(['a', 1, null, null]);
    }

    public function testFbqRemovesNullArgsFromEnd()
    {
        $pixel = $this->newPixel();

        $this->assertEquals("fbq('track', 1);", $pixel->fbq('track', 1, null, null));
    }

    protected function newPixel()
    {
        return new \Okneloper\FacebookPixel\FacebookPixel(12345);
    }
}
