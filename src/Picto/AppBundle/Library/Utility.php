<?php

namespace Picto\AppBundle\Library;

class Utility
{

    /**
     * Generates a random string of a certain length.
     *
     * @param integer $length
     * @return string
     */
    public static function getRandomString($length=8)
    {
        $length = abs((int)$length);
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $poolLen = strlen($pool)-1;

        $random = '';
        for ($i=0; $i<$length; $i++) {
            $random .= $pool[mt_rand(0, $poolLen)];
        }

        return $random;
    }

}
