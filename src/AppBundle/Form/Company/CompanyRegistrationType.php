<?php

namespace AppBundle\Form\Company;

use AppBundle\Form\Logo\LogoType;
use Symfony\Component\Validator\Constraints\NotBlank;
use AppBundle\Entity\Company;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
// for events
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use AppBundle\Entity\City;
use AppBundle\Entity\Area;

class CompanyRegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('corporateName', TextType::class, array(
                'label' => 'Raison social',
            ))
            ->add('address', TextType::class, array(
                'label' => 'Adresse',
            ))
            ->add('postCode', IntegerType::class, array(
                'label' => 'Code postal',
            ))
            /*->add('city', EntityType::class, array(
                'class' => City::class,
                'label' => 'Ville',
                'choice_label' => 'name',
                'placeholder' => 'Choisir une ville ',
                'multiple' => false,
                'expanded' => false,
                'mapped' => false,
                'constraints' => array(new NotBlank(array('message' => 'Ville est vide')))
            ))
            ->add('area', EntityType::class, array(
                'class' => Area::class,
                //'choices' => $areas,
                'choice_label' => 'name',
                'label' => 'Région',
                'placeholder' => 'Choisir une region',
                'multiple' => false,
                'expanded' => false,
                'required' => false,
                //'allow_extra_fields' => true

            ))*/
            ->add('mobile', TextType::class, array(
                'label' => 'Mobile',
            ))
            ->add('logo', LogoType::class, array(
                'label' => 'Logo',
            ))
        ;


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Company::class,
        ));
    }

    public function getBlockPrefix()
    {
        return 'company';
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }
}