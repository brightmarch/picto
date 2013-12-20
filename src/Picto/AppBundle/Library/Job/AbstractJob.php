<?php

namespace Picto\AppBundle\Library\Job;

use BCC\ResqueBundle\ContainerAwareJob;

abstract class AbstractJob extends ContainerAwareJob
{

    public function get($service)
    {
        return $this->getContainer()
            ->get($service);
    }

    public function getEntityManager()
    {
        return $this->get('doctrine')
            ->getManager();
    }

}
