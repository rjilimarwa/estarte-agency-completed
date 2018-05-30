<?php

namespace AppBundle\Form\Property;

use AppBundle\Entity\Category;
use AppBundle\Entity\Operation;
use AppBundle\Entity\Situation;
use AppBundle\Form\Photo\PhotoType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use AppBundle\Form\Image\ImageType;

// for events
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use AppBundle\Entity\City;
use AppBundle\Entity\Area;


class PropertyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, array(
                'label' => 'Titre',
            ))
            ->add('operation', EntityType::class, array(
                'class' => Operation::class,
                'label' => 'Opération',
                'choice_label' => 'name',
                'placeholder' => 'Choisir une opération ',
                'multiple' => false,
                'expanded' => false,
            ))
            ->add('description', TextareaType::class, array(
                'label' => 'Description',
                'required' => false,
                'attr' => array('rows' => '5')
            ))
            ->add('price', IntegerType::class, array(
                'label' => 'Prix',
                'required' => true,
            ))
            ->add('floorArea', IntegerType::class, array(
                'label' => 'Superficie',
                'required' => true,
            ))
            ->add('plotArea', IntegerType::class, array(
                'label' => 'Superficie couverte',
                'required' => false,
            ))
            ->add('roomNumber', ChoiceType::class, array(
                'label' => 'Nombre chambres',
                'required' => false,
                'placeholder' => 'Nombre chambres',
                'choices' => array(
                    '0' => '0',
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
            ->add('livingRoomNumber', ChoiceType::class, array(
                'label' => 'Nombre salons',
                'required' => false,
                'placeholder' => 'Nombre salons',
                'choices' => array(
                    '0' => '0',
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
                'placeholder' => 'Nombre salle de bains',
                'choices' => array(
                    '0' => '0',
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
                'label' => 'Nombre cuisines',
                'required' => false,
                'placeholder' => 'Nombre cuisine',
                'choices' => array(
                    '0' => '0',
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
            ->add('balcony', CheckboxType::class, array(
                'label' => 'Balcon',
                'required' => false
            ))
            ->add('terrace', ChoiceType::class, array(
                'label' => 'Terrasses',
                'required' => false,
                'placeholder' => 'Nombre terrasse',
                'choices' => array(
                    '0' => '0',
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
            ->add('garden', CheckboxType::class, array(
                'label' => 'Jardin',
                'required' => false
            ))
            ->add('garage', CheckboxType::class, array(
                'label' => 'Garage',
                'required' => false
            ))
            ->add('parking', CheckboxType::class, array(
                'label' => 'Parking',
                'required' => false
            ))
            ->add('floorNumber', ChoiceType::class, array(
                'label' => 'Num étage',
                'required' => false,
                'placeholder' => 'Numéro étage',
                'choices' => array(
                    '0' => '0',
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
            ->add('elevator', CheckboxType::class, array(
                'label' => 'ascenseur',
                'required' => false,
            ))
            ->add('heating', CheckboxType::class, array(
                'label' => 'Chauffage',
                'required' => false,
            ))
            ->add('airConditioner', CheckboxType::class, array(
                'label' => 'Climatisation',
                'required' => false,
            ))
            ->add('alarmSystem', CheckboxType::class, array(
                'label' => 'Systéme alarme',
                'required' => false
            ))
            ->add('wifi', CheckboxType::class, array(
                'label' => 'Wifi',
                'required' => false
            ))
            ->add('swimmingPool', CheckboxType::class, array(
                'label' => 'Piscine',
                'required' => false
            ))
            ->add('category', EntityType::class, array(
                'class' => Category::class,
                'label' => 'Catégorie',
                'choice_label' => 'name',
                'placeholder' => 'Choisir Catégorie ',
                'multiple' => false,
                'expanded' => false,
            ))
            ->add('situation', EntityType::class, array(
                'class' => Situation::class,
                'label' => 'Situation',
                'choice_label' => 'name',
                'placeholder' => 'Situation ',
                'multiple' => false,
                'expanded' => false,
            ))
            ->add('city', EntityType::class, array(
                'class' => City::class,
                'label' => 'Ville',
                'choice_label' => ucfirst('name'),
                'placeholder' => 'Choisir une ville ',
                'multiple' => false,
                'expanded' => false,
            ))
            ->add('photos', CollectionType::class, array(
                'entry_type' => PhotoType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => false,
                'required'=> false,
            ));

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $object = $event->getData();
            $form = $event->getForm();

            $form->add('displayPrice', CheckboxType::class, array(
                'label' => 'Afficher prix',
                'required' => false,
                'attr' => array('checked' => !$object || !$object->getId())
            ));

            $form->add('active', CheckboxType::class, array(
                'label' => 'Publier',
                'required' => false,
                'attr' => array('checked' => !$object || !$object->getId())
            ));

            $form->add('image', ImageType::class, array(
                'required' => !$object || !$object->getImage(),
            ));
        });

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
            'data_class' => 'AppBundle\Entity\Property',
        ));
    }


    public function getBlockPrefix()
    {
        return 'property';
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }
}