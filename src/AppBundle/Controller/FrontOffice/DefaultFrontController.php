<?php

namespace AppBundle\Controller\FrontOffice;

use AppBundle\Entity\MessageReceived;
use AppBundle\Form\Message\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultFrontController extends Controller
{

    /**
     * @Route("/", name="front_office_homepage")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $sliders = $em->getRepository('AppBundle:Slider')->findBy(array('active'=>true), array('id'=>'asc'));
        $entities = $em->getRepository('AppBundle:Property')->findBy(array('active'=>1, 'state'=>'valid'), array('id' => 'desc'), 24, 0);

        $statisticProperties = $em->getRepository('AppBundle:Property')->StatisticProperties();
        $countPropertiesOnline = $em->getRepository('AppBundle:Property')->countPropertiesOnline();
        $countUsers = $em->getRepository('AppBundle:User')->countUsers();

        return $this->render('frontOffice/default/index.html.twig', array(
            'latestProperties' => $entities,
            'sliders'=> $sliders,
            'ids' => $this->get('my_favorites.properties')->getFavorites(),
            'countProperties'=> $statisticProperties['countProperties'],
            'countViews' => $statisticProperties['countViews'],
            'countPropertiesOnline'=> $countPropertiesOnline['countOnline'],
            'countUsers'=> $countUsers['countUsers']
        ));
    }

    public function headerAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $categories = $em->getRepository('AppBundle:Category')->findAll();

        return $this->render('frontOffice/includes/header.html.twig', array(
            'categories'=> $categories,
        ));
    }

    /**
     * @Route("/contactez-nous", name="front_office_contact")
     */
    public function contactAction(Request $request)
    {
        $entity = new MessageReceived();
        $form = $this->createForm(ContactType::class, $entity);
        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em->persist($entity);
                $em->flush();

                $view = $this->renderView('frontOffice/form/form_contact_success.html.twig');

                $message = \Swift_Message::newInstance()
                    ->setSubject($form->get('subject')->getData())
                    ->setFrom(array(
                        $this->getParameter('mailer_user') => $form->get('fullName')->getData()
                    ))
                    ->setTo($this->getParameter('mailer_user'))
                    ->setReplyTo($form->get('email')->getData())
                    ->setBody(
                        $this->renderView(
                            'emails/contact.html.twig', array(
                                'name' => $form->get('fullName')->getData(),
                                'tel'=> $form->get('phone')->getData(),
                                'subject'=> $form->get('subject')->getData(),
                                'message'=> $form->get('message')->getData(),
                            )
                        ),
                        'text/html'
                    )

                ;
                $this->get('mailer')->send($message);

                $message2 = \Swift_Message::newInstance()
                    ->setSubject('Nouveau message de'. $form->get('fullName')->getData())
                    ->setFrom(array(
                        $this->getParameter('mailer_user') => $this->getParameter('sender_name')
                    ))
                    ->setTo('3m.immobiliere@gmail.com')
                    ->setReplyTo($form->get('email')->getData())
                    ->setBody(
                        $this->renderView(
                            'emails/contact_copy.html.twig', array(
                                'name' => $form->get('fullName')->getData(),
                                'tel'=> $form->get('phone')->getData(),
                                'subject'=> $form->get('subject')->getData(),
                                'message'=> $form->get('message')->getData(),
                                'entity'=> $entity
                            )
                        ),
                        'text/html'
                    )
                ;
                $this->get('mailer')->send($message2);

                return new JsonResponse(array(
                    'view' => $view,
                ));

            } else {
                $view = $this->renderView('frontOffice/form/form_errors.html.twig', array(
                    'form' => $form->createView()));

                return new JsonResponse(array(
                    'view' => $view,
                    'result' => 0,
                    'message' => 'Formulaire non valide',
                ));
            }
        }

        return $this->render('frontOffice/default/contact.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/presentation", name="front_office_about")
     */
    public function aboutAction()
    {
        return $this->render('frontOffice/default/about.html.twig');
    }

    /**
     * @Route("/conditions-utilisation", name="front_office_terms_conditions")
     */
    public function conditionAction()
    {
        return $this->render('frontOffice/default/term_condition.html.twig');
    }


    public function servicesAction()
    {
        return $this->render('frontOffice/default/services.html.twig', array(
        ));
    }

    /**
     * @Route("/register/get-areas", name="front_office_get_areas")
     */
    public function selectRegionsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $city = $request->request->get('city');

        $entityCity = $em->getRepository('AppBundle:City')->findOneBySlug($city);

        $entities = $em->getRepository('AppBundle:Area')->findByCity($entityCity);
        return $this->render('frontOffice/property/select_areas.html.twig', array(
                'areas' => $entities,
            )
        );
    }
}
