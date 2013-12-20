<?php

namespace Picto\AppBundle\Tests\Controller;

use Picto\AppBundle\Tests\TestCase;

class IndexControllerTest extends TestCase
{

    public function testIndex()
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $message = "Simple Image Hosting";
        $this->assertContains($message, $client->getCrawler()->text());
    }

}
