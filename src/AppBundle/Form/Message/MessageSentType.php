<?php

namespace AppBundle\Form\Message;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class MessageSentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, array(
                'label' => 'Email',
                'required' => true,
            ))
            ->add('subject', TextType::class, array(
                'label' => 'Sujet',
                'required' => true,
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
            'data_class' => 'AppBundle\Entity\MessageSent',
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