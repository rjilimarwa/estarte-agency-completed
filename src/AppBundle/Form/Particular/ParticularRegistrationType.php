<?php

namespace AppBundle\Form\Particular;

use AppBundle\Entity\Particular;

use AppBundle\Repository\CountryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParticularRegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, array(
                'label' => 'Nom',
            ))
            ->add('lastName', TextType::class, array(
                'label' => 'Prénom',
            ))
            ->add('mobile', TextType::class, array(
                'label' => 'Tél mobile',
            ))
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Particular::class,
        ));
    }

    public function getBlockPrefix()
    {
        return 'particular';
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }
}