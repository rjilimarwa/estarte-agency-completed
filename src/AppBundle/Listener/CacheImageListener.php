<?php
namespace AppBundle\Listener;

use AppBundle\Entity\Photo;
use AppBundle\Entity\Slider;
use Doctrine\ORM\Event\LifecycleEventArgs;
use AppBundle\Entity\Image;
 
class CacheImageListener
{
    protected $cacheManager;
 
    public function __construct($cacheManager)
    {
        $this->cacheManager = $cacheManager;
    }
 
    // en cas la modification l'image d'origine
	public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
 
        if ($entity instanceof Image or $entity instanceof Photo or $entity instanceof Slider) {
            // vider le cache des vignettes
            $this->cacheManager->remove($entity->getPreWebPath());
        }

    }

	// en cas du supprission l'image d'origine 
    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof Image or $entity instanceof Photo or $entity instanceof Slider) {
            // vider le cache des vignettes
            $this->cacheManager->remove($entity->getPreWebPath());
        }
    }

}
