<?php

namespace AppBundle\Form\Particular;

use AppBundle\Entity\Particular;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParticularProfileType extends AbstractType
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
            ->add('civility', ChoiceType::class, array(
                'label' => 'Civilité',
                'choices' => array(
                    'Choisir civilité' => '',
                    'M' => 'M',
                    'Mme' => 'Mme',
                ),
                'required' => false,
            ))
            ->add('mobile', TextType::class, array(
                'label' => 'Mobile',
                'required' => true,
            ))
            ->add('phone', TextType::class, array(
                'label' => 'Téléphone fixe',
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