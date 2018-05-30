<?php

namespace AppBundle\Form\Message;

use AppBundle\Entity\MessageReceived;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fullName', TextType::class, array(
                'label' => 'Nom et prénom',
            ))
            ->add('email', EmailType::class, array(
                'label' => 'Email',
            ))
            ->add('phone', TextType::class, array(
                'label' => 'Tél',
                'required' => false,
            ))
            ->add('subject', TextType::class, array(
                'label' => 'Sujet',
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
            'data_class' => MessageReceived::class,
        ));
    }

    public function getBlockPrefix()
    {
        return 'contact';
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }
}