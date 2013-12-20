<?php

namespace Picto\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ComputerUploadType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('image', 'file');
    }

    public function getName()
    {
        return 'computerUpload';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Picto\AppBundle\Entity\ComputerUpload',
            'intention' => 'upload'
        ]);
    }

}
