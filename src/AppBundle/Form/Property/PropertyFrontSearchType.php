<?php

namespace AppBundle\Form\Property;

use AppBundle\Entity\Category;
use AppBundle\Entity\Operation;
use AppBundle\Entity\Situation;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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


class PropertyFrontSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('operation', EntityType::class, array(
                'class' => Operation::class,
                'label' => 'Opération',
                'choice_label' => function($operation){
                    return ucfirst($operation->getName());
                },
                'choice_value' => function (Operation $entity = null) {
                    return $entity ? $entity->getName() : '';
                },
                'placeholder' => 'Opération ',
                'multiple' => false,
                'expanded' => false,
                'required' => false
            ))
            ->add('situation', EntityType::class, array(
                'class' => Situation::class,
                'label' => 'Situation',
                'choice_label' => function($situation){
                    return ucfirst($situation->getName());
                },
                'choice_value' => function (Situation $entity = null) {
                    return $entity ? $entity->getSlug() : '';
                },
                'placeholder' => 'Situation ',
                'multiple' => false,
                'expanded' => false,
                'required' => false
            ))
            ->add('ref', TextType::class, array(
                'label' => 'Réf',
                'required' => false,
            ))
            ->add('keyWord', TextType::class, array(
                'label' => 'Un mot clé',
                'required' => false,
                'mapped' => false
            ))
            ->add('category', EntityType::class, array(
                'class' => 'AppBundle:Category',
                'label' => 'Type de bien',
                'choice_label' => function ($category) {
                    return ucfirst($category->getName());
                },
                'choice_value' => function (Category $entity = null) {
                    return $entity ? $entity->getSlug() : '';
                },
                'placeholder' => 'Type de bien ',
                'multiple' => false,
                'expanded' => false,
                'required' => false,
            ))
            ->add('city', EntityType::class, array(
                'class' => City::class,
                'label' => 'Gouvernorat',
                'choice_label' => 'name',
                'choice_value' => function (City $entity = null) {
                    return $entity ? $entity->getSlug() : '';
                },
                'placeholder' => 'Gouvernorat ',
                'multiple' => false,
                'expanded' => false,
                'required' => false,
            ))
            ->add('priceMin', IntegerType::class, array(
                'label' => 'Prix(min)',
                'required' => false,
                'mapped' => false
            ))
            ->add('priceMax', IntegerType::class, array(
                'label' => 'Prix(max)',
                'required' => false,
                'mapped' => false
            ))
            ->add('floorAreaMin', IntegerType::class, array(
                'label' => 'Superficie(min)',
                'required' => false,
                'mapped' => false,
            ))
            ->add('floorAreaMax', IntegerType::class, array(
                'label' => 'Superficie(max)',
                'required' => false,
                'mapped' => false,
            ))
            ->add('roomNumberMin', ChoiceType::class, array(
                'label' => 'Nombre de pièces min',
                'required' => false,
                'placeholder' => 'Nombre de pièces min',
                'choices' => array(
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                    '7' => '7',
                    '8' => '8',
                    '9' => '9',
                    '10' => '10',
                ),
                'mapped'=> false,
            ))
            ->add('roomNumberMax', ChoiceType::class, array(
                'label' => 'Nombre de pièces max',
                'required' => false,
                'placeholder' => 'Nombre de pièces max',
                'choices' => array(
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                    '7' => '7',
                    '8' => '8',
                    '9' => '9',
                    '10' => '10',
                ),
                'mapped'=> false
            ))
            /*->add('livingRoomNumber', ChoiceType::class, array(
                'label' => 'Salons',
                'required' => false,
                'placeholder' => 'Salons max',
                'choices' => array(
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                    '7' => '7',
                    '8' => '8',
                    '9' => '9',
                    '10' => '10',
                )
            ))
            ->add('bathRoomNumber', ChoiceType::class, array(
                'label' => 'Nombre salle de bains',
                'required' => false,
                'placeholder' => 'Salle de bains max',
                'choices' => array(
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                    '7' => '7',
                    '8' => '8',
                    '9' => '9',
                    '10' => '10',
                )
            ))
            ->add('kitchenNumber', ChoiceType::class, array(
                'label' => 'Cuisines max',
                'required' => false,
                'placeholder' => 'Cuisine',
                'choices' => array(
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                    '7' => '7',
                    '8' => '8',
                    '9' => '9',
                    '10' => '10',
                )
            ))*/
            ->add('garage', CheckboxType::class, array(
                'label' => 'Garage',
                'required' => false
            ))
            ->add('garden', CheckboxType::class, array(
                'label' => 'Jardin',
                'required' => false,
            ))
            ->add('parking', CheckboxType::class, array(
                'label' => 'Parking',
                'required' => false
            ))
            ->add('airConditioner', CheckboxType::class, array(
                'label' => 'Climatisation',
                'required' => false
            ))
            ->add('heating', CheckboxType::class, array(
                'label' => 'Chauffage',
                'required' => false
            ))
            ->add('alarmSystem', CheckboxType::class, array(
                'label' => 'Systéme alarme',
                'required' => false
            ))
            ->add('elevator', CheckboxType::class, array(
                'label' => 'Ascenseur',
                'required' => false
            ))
            ->add('swimmingPool', CheckboxType::class, array(
                'label' => 'Piscine',
                'required' => false
            ));

        $formModifier = function (FormInterface $form, City $city = null) {

            $areas = null === $city ? array() : $city->getAreas();

            $form->add('area', EntityType::class, array(
                'class' => Area::class,
                'choices' => $areas,
                'choice_label' => 'name',
                'choice_value' => function (Area $entity = null) {
                    return $entity ? $entity->getSlug() : '';
                },
                'label' => 'Ville',
                'placeholder' => 'Ville',
                'multiple' => false,
                'expanded' => false,
                'required' => false,
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
            'data_class' => 'AppBundle\Entity\Property',
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