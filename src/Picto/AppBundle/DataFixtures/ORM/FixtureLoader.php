<?php

namespace Picto\AppBundle\DataFixtures\ORM;

use Picto\AppBundle\Entity\Image;
use Picto\AppBundle\Library\Constants;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use \DateTime;

class FixtureLoader extends AbstractFixture
    implements FixtureInterface, ContainerAwareInterface
{

    /** @var Symfony\Component\DependencyInjection\ContainerInterface */
    private $container;

    public function setContainer(ContainerInterface $container=null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $entityManager)
    {
        $parameters = $this->container->parameters;

        $image = new Image;
        $image->setHash($parameters['picto_test_image_hash'])
            ->setRawHash($parameters['picto_test_image_raw_hash'])
            ->setFilePath($parameters['picto_test_image_path'])
            ->setFileName(basename($parameters['picto_test_image_path']))
            ->setFileSize(filesize($parameters['picto_test_image_path']))
            ->setFileExtension($parameters['picto_test_image_extension'])
            ->setContentType($parameters['picto_test_image_mime_type']);

        $entityManager->persist($image);
        $entityManager->flush();

        $this->addReference('image', $image);

        return true;
    }

}
