<?php

namespace AppBundle\Form\Area;

use AppBundle\Entity\City;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;

class AreaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'label' => 'Nom ville',
            ))
            ->add('city', EntityType::class, array(
                'class' => City::class,
                'label' => 'Gouvernorat',
                'choice_label' => 'name',
                'placeholder' => 'Choisir une gouvernorat ',
                'multiple' => false,
                'expanded' => false,
            ))
        ;
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Area',
        ));
    }


    public function getBlockPrefix()
    {
        return null;
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }
}