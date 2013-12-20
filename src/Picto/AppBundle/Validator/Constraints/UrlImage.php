<?php

namespace Picto\AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UrlImage extends Constraint
{

    /** @var string */
    public $message = "Invalid URL image.";

    public function validatedBy()
    {
        return 'url_image';
    }

}
