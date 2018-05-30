<?php
namespace AppBundle\Twig;

class AppExtension extends \Twig_Extension
{

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('activate', array($this, 'activateFilter'), array('is_safe' => array('html'), 'needs_environment' => true)),
            new \Twig_SimpleFilter('label_etat', array($this, 'etatFilter'), array('is_safe' => array('html'))),
            new \Twig_SimpleFilter('boolean', array($this, 'booleanFilter'), array('is_safe' => array('html'))),
            new \Twig_SimpleFilter('superficie', array($this, 'superficieFilter'), array('is_safe' => array('html'))),
            new \Twig_SimpleFilter('locked', array($this, 'lockedFilter'), array('is_safe' => array('html'), 'needs_environment' => true)),
        );
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('favorite_btn', array($this, 'favoriteBtn'), array('is_safe' => array('html'), 'needs_environment' => true)),
        );
    }

    /**
     * render activate template
     *
     * @param \Twig_Environment $twig
     * @param $route
     * @param $id
     * @param $state
     * @return string
     */
    public function activateFilter(\Twig_Environment $twig, $value, $route, $id, $state)
    {
        return $twig->render('backOffice/twig/activate.html.twig', array(
            'route' => $route,
            'id' => $id,
            'state' => $state
        ));
    }


    /**
     * render icon check or times
     *
     * @param $value
     * @return string
     */
    public function booleanFilter($value)
    {
        if (is_bool($value)) {
            if ($value) {
                return "<i class=\"fa fa-check text-success bigger-120\"></i>";
            } else {
                return "<i class=\"fa fa-times text-danger bigger-120\"></i>";
            }
        } else {
            if ($value === null) {
                return "<i class=\"fa fa-times text-danger bigger-120\"></i>";
            } else {
                return $value;
            }
        }
    }

    /**
     * render icon check or times
     *
     * @param $value
     * @return string
     */
    public function superficieFilter($value)
    {
        if ($value == null) {
            return "<i class=\"fa fa-times text-danger bigger-120\"></i>";
        } else {
            return $value . 'm<sup>2</sup>';
        }
    }

    /**
     * render colored label
     *
     * @param $value
     * @return string
     */
    public function etatFilter($value)
    {
        if (is_bool($value)) {
            if ($value) {
                return "<span class=\"label label-table label-success\">Activé</span>";

            } else {
                return "<span class=\"label label-table label-danger\">Désactivé</span>";
            }
        }
    }

    /**
     * render locked template
     *
     * @param \Twig_Environment $twig
     * @param $route
     * @param $id
     * @param $state
     * @return string
     */
    public function lockedFilter(\Twig_Environment $twig, $value, $route, $id, $state)
    {
        return $twig->render('backOffice/twig/locked.html.twig', array(
            'route' => $route,
            'id' => $id,
            'state' => $state
        ));
    }

    /**
     * render favorite_btn template
     *
     * @param \Twig_Environment $twig
     * @param $id
     * @param $favExist
     * @return string
     */
    public function favoriteBtn(\Twig_Environment $twig, $id, $favExist)
    {
        return $twig->render('FrontOffice/Twig:favorite_btn.html.twig', array(
            'id' => $id,
            'favExist' => $favExist
        ));
    }

    public function getName()
    {
        return 'app_extension';
    }
}