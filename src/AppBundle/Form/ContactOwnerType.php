<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactOwnerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fullName', TextType::class, array(
                'label' => 'Nom et prénom',
                'constraints' => array(
                    new NotBlank(array('message' => 'Nom et prénom sont vide'))
                )
            ))
            ->add('email', EmailType::class, array(
                'label' => 'Email',
                'constraints'=> array(
                    new NotBlank(array('message' => 'Email est vide')),
                    new Email(array('message'=> 'Format email non valide'))
                )
            ))
            ->add('phone', IntegerType::class, array(
                'label' => 'Téléphone',
                'required' => false,
            ))
            ->add('message', TextareaType::class, array(
                'label' => 'Message',
                'attr'=> array('rows'=>'10')
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => null,
        ));
    }

    public function getBlockPrefix()
    {
        return 'contact_owner';
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }
}