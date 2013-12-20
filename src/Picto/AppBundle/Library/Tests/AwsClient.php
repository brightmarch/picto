<?php

namespace Picto\AppBundle\Library\Tests;

use Picto\AppBundle\Library\Tests\S3Client;

class AwsClient
{

    /** @const string */
    const SERVICE_S3 = 's3';

    public function get($serviceName)
    {
        $service = null;

        if (self::SERVICE_S3 === $serviceName) {
            $service = new S3Client;
        }

        return $service;
    }

}
