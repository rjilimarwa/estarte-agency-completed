<?php

namespace AppBundle\Form\Company;

use AppBundle\Entity\Company;
use AppBundle\Form\Logo\LogoType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class CompanyProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('corporateName', TextType::class, array(
                'label' => 'Raison social',
                'required' => true,
            ))
            ->add('address', TextType::class, array(
                'label' => 'Adresse',
            ))
            ->add('postCode', IntegerType::class, array(
                'label' => 'Code postal',
            ))
            ->add('mobile', TextType::class, array(
                'label' => 'Mobile',
            ))
            ->add('taxRegistrationNumber', TextType::class, array(
                'label' => 'Code TVA',
                'required'=> false,
            ))
            ->add('logo', LogoType::class, array(
                'label' => 'Logo',
                'required'=> false
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
        return 'particular';
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }
}