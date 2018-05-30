<?php
namespace AppBundle\Twig;
 
use Symfony\Component\Intl\Intl;

class CountryExtension extends \Twig_Extension {
	public function getFilters()
    {
    	return array(
        	new \Twig_SimpleFilter('country', array($this, 'countryFilter')),
        );
    }
 
	public function countryFilter($countryCode, $locale = "en")
	{
		$c = Intl::getRegionBundle()->getCountryNames($locale);
 
		return array_key_exists($countryCode, $c)
			? $c[$countryCode]
			: $countryCode;
	}
 
	public function getName()
	{
		return 'country_extension';
    }
}