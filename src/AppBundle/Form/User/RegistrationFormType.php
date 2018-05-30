<?php

namespace AppBundle\Form\User;

use AppBundle\Form\Company\CompanyProfileType;
use AppBundle\Form\Company\CompanyRegistrationType;
use AppBundle\Form\Particular\ParticularProfileType;
use AppBundle\Form\Particular\ParticularRegistrationType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Routing\Router;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use AppBundle\Entity\City;
use AppBundle\Entity\Area;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    protected $router;
    protected $requestStack;


    public function __construct(Router $router, RequestStack $requestStack)
    {
        $this->router = $router;
        $this->requestStack = $requestStack;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $type = $this->requestStack->getCurrentRequest()->query->get('type');
        if (isset($type) and $type != '' and $type == 'company') {
            $builder
                ->add('company', CompanyRegistrationType::class)
                ->add('city', EntityType::class, array(
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


        } elseif (isset($type) and $type != '' and $type == 'particular') {
            $builder->add('particular', ParticularRegistrationType::class);
        } else {
            throw new NotFoundHttpException('Sorry not existing!');
        }

        $builder->remove('username');
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }

    // For Symfony 2.x
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}