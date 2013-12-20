<?php

namespace Picto\AppBundle\Tests;

use Liip\FunctionalTestBundle\Test\WebTestCase;

abstract class TestCase extends WebTestCase
{

    public function setUp()
    {
        // Clear out the upload queue.
        $this->getContainer()
            ->get('bcc_resque.resque')
            ->clearQueue('upload');
    }

    protected function getEntityManager()
    {
        return $this->getContainer()
            ->get('doctrine')
            ->getManager();
    }

}
