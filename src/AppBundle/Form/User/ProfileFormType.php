<?php

namespace AppBundle\Form\User;

use AppBundle\Form\Administrator\AdministratorProfileType;
use AppBundle\Form\Company\CompanyProfileType;
use AppBundle\Form\Particular\ParticularProfileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use AppBundle\Entity\City;
use AppBundle\Entity\Area;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProfileFormType extends AbstractType
{
    protected $security;

    public function __construct(AuthorizationChecker $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->remove('username');
        if
        ($this->security->isGranted('ROLE_ADMIN')) {
            $builder->add('administrator', AdministratorProfileType::class);
            return false;
        } elseif ($this->security->isGranted('ROLE_PARTICULAR')) {
            $builder->add('particular', ParticularProfileType::class);
        } else {
            $builder->add('company', CompanyProfileType::class);
        }
        $builder->add('city', EntityType::class, array(
            'class' => City::class,
            'label' => 'Ville',
            'choice_label' => 'name',
            'placeholder' => 'Choisir une ville ',
            'multiple' => false,
            'expanded' => false,
            'constraints' => array(new NotBlank(array('message' => 'Ville est vide')))
        ));
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
                'constraints' => array(new NotBlank(array('message' => 'Région est vide')))
            ));
        };

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($formModifier) {
                $data = $event->getData();

                //don't forget this test to not get error call to non object function
                // if city is mapped false
                if (null === $data) {
                    return;
                }

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
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\ProfileFormType';

    }

    public function getBlockPrefix()
    {
        return 'app_user_profile';
    }

    // For Symfony 2.x
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}