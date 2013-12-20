<?php

namespace Picto\AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

use Symfony\Component\DependencyInjection\ContainerInterface;

use Intervention\Image\Exception\InvalidImageDataStringException;

class UrlImageValidator extends ConstraintValidator
{

    /** @var Symfony\Component\DependencyInjection\ContainerInterface */
    private $container;

    public function __construct(ContainerInterface $container=null)
    {
        $this->container = $container;
    }

    public function validate($value, Constraint $constraint)
    {
        $imageEditor = $this->container
            ->get('picto_app.image_editor');

        try {
            $imageEditor->make(@file_get_contents($value));
        } catch (InvalidImageDataStringException $e) {
            $this->context->addViolation($constraint->message);
        }

        return true;
    }

}
