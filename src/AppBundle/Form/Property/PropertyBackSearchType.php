<?php

namespace AppBundle\Form\Property;

use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

// for events
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use AppBundle\Entity\City;
use AppBundle\Entity\Area;


class PropertyBackSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ref', TextType::class, array(
                'label' => 'Réf',
                'required' => false,
            ))
            ->add('title', TextType::class, array(
                'label' => 'Titre',
                'required' => false,
            ))
            ->add('priceMin', IntegerType::class, array(
                'label' => 'Prix(min)',
                'required' => false,
            ))
            ->add('priceMax', IntegerType::class, array(
                'label' => 'Prix(max)',
                'required' => false,
            ))
            ->add('floorArea', IntegerType::class, array(
                'label' => 'Superficie(max)',
                'required' => false,
            ))
            ->add('roomNumber', IntegerType::class, array(
                'label' => 'Nombre chambres',
                'required' => false,
            ))
            ->add('livingRoomNumber', IntegerType::class, array(
                'label' => 'Nombre salons',
                'required' => false,
            ))
            ->add('bathRoomNumber', IntegerType::class, array(
                'label' => 'Nombre salle de bains',
                'required' => false,
            ))
            ->add('kitchenNumber', IntegerType::class, array(
                'label' => 'Nombre cuisines',
                'required' => false,
            ))
            ->add('garden', CheckboxType::class, array(
                'label'=> 'Jardin',
                'required'=> false
            ))
            ->add('garage', CheckboxType::class, array(
                'label'=> 'Garage',
                'required'=> false
            ))
            ->add('parking', CheckboxType::class, array(
                'label'=> 'Parking',
                'required'=> false
            ))
            ->add('elevator', CheckboxType::class, array(
                'label'=> 'ascenseur',
                'required'=> false,
            ))
            ->add('category', EntityType::class, array(
                'class' => 'AppBundle:Category',
                'label' => 'Catégorie',
                'choice_label' => 'name',
                'placeholder' => 'Choisir Catégorie ',
                'multiple' => false,
                'expanded' => false,
            ))
            ->add('city', EntityType::class, array(
                'class' => City::class,
                'label' => 'Ville',
                'choice_label' => 'name',
                'placeholder' => 'Choisir une ville ',
                'multiple' => false,
                'expanded' => false,
                'mapped'=> false,
            ))
        ;

        $formModifier = function (FormInterface $form, City $city = null) {

            $areas = null === $city ? array() : $city->getAreas();

            $form->add('area', EntityType::class, array(
                'class' => Area::class,
                'choices' => $areas,
                'choice_label' => 'name',
                'label' => 'Région',
                'placeholder' => 'Choisir une region',
                'multiple' => false,
                'expanded' => false,
                'required' => false,
                //'allow_extra_fields' => true

            ));
        };

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($formModifier) {
                $data = $event->getData();

                //don't forget this test to not get error call to non object function
                /* if (null === $data) {
                     return;
                 }*/

                $formModifier($event->getForm(), $data->getCity());

            }
        );

        $builder->get('city')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifier) {
                // It's important here to fetch $event->getForm()->getData(), as
                // $event->getData() will get you the client data (that is, the ID)
                $city = $event->getForm()->getData();

                // since we've added the listener to the child, we'll have to pass on
                // the parent to the callback functions!
                $formModifier($event->getForm()->getParent(), $city);
            }
        );

        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            $event->stopPropagation();
        }, 90000000000000000); // Always set a higher priority than ValidationListener
        // very important to set it highet to catch form error and display them
        // make experienc for this. don't forget it
        //90000000000000000
        // priority of other eventListener beween -255 and 255

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'      => 'AppBundle\Entity\Property',
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