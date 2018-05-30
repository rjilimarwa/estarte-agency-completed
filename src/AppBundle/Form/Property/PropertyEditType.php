<?php

namespace AppBundle\Form\Property;

use Symfony\Component\Form\FormBuilderInterface;

class PropertyEditType extends PropertyType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

    }


    public function getBlockPrefix()
    {
        return 'property';
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }
}