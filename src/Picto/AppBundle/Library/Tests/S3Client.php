<?php

namespace Picto\AppBundle\Library\Tests;

class S3Client
{

    public function putObject(array $details)
    {
        $url = 'https://s3.amazonaws.com/picto.images/_test_image.png';

        $results = [
            'ObjectURL' => $url
        ];

        return $results;
    }

}
