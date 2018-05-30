<?php

namespace AppBundle\Form\Particular;

use AppBundle\Repository\CountryRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ParticularSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('firstName', TextType::class, array(
                'label' => 'Nom',
                'required' => false,
            ))
            ->add('lastName', TextType::class, array(
                'label' => 'Prénom',
                'required' => false,
            ))
            ->add('civility', ChoiceType::class, array(
                'label' => 'Civilité',
                'choices' => array(
                    'Aucun' => '',
                    'M' => 'M',
                    'Mme' => 'Mme',
                ),
                'required'=> false,
            ))
            ->add('email', TextType::class, array(
                'label' => 'Email',
                'required' => false,
                'mapped'=> false,
            ))
            ->add('phone', TextType::class, array(
                'label' => 'Téléphone fixe',
                'required' => false,
            ))
            ->add('mobile', TextType::class, array(
                'label' => 'Mobile',
                'required' => false,
            ))
            ->add('fax', TextType::class, array(
                'label' => 'Fax',
                'required' => false,
            ))
            ->add('address', TextType::class, array(
                'label' => 'Adresse',
                'required' => false,
            ))
            ->add('postCode', IntegerType::class, array(
                'label' => 'Code postal',
                'required' => false,
            ))
            ->add('country', EntityType::class, array(
                'label' => 'Pays',
                'class' => 'AppBundle\Entity\Country',
                'choice_label' => 'nameFr',
                'placeholder' => 'Pays',
                'multiple' => false,
                'expanded' => false,
                'required' => false,
                'query_builder' => function (CountryRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nameFr', 'asc');
                },
            ))
            ->add('city', TextType::class, array(
                'label' => 'Ville',
                'required' => false,
            ))
            ->add('corporateName', TextType::class, array(
                'label' => 'Raison social',
                'required' => false,
            ))
            ->add('taxRegistrationNumber', TextType::class, array(
                'label' => 'Matricule Fiscal',
                'required' => false,
            ))
            ->add('activity', TextType::class, array(
                'label' => 'Activité',
                'required' => false,
            ))
            ->add('enabled', ChoiceType::class, array(
                'label' => 'Etat',
                'choices' => array(
                    'Aucun' => '',
                    'Activé' => '1',
                    'Désactivé' => '0',
                ),
                'required'=> false,
                'mapped'=> false,
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Particular',
            'csrf_protection'=> false,
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