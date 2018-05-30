<?php

namespace AppBundle\Form\Administrator;

use AppBundle\Entity\Administrator;
use AppBundle\Form\User\UserEditType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdministratorEditType extends AdministratorType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

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
            ->add('user', UserEditType::class, array(
                'label' => false,
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
        return 'administrator_edit';
    }


    public function getName()
    {
        return $this->getBlockPrefix();
    }
}