<?php

namespace AppBundle\Form\Administrator;

use AppBundle\Entity\Administrator;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdministratorProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, array(
                'label' => 'Nom',
            ))
            ->add('lastName', TextType::class, array(
                'label' => 'Prénom',
                'required' => false,
            ))
            ->add('mobile', TextType::class, array(
                'label' => 'Mobile',
            ))
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Administrator::class,
        ));
    }

    public function getBlockPrefix()
    {
        return 'administrator';
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }
}