<?php
namespace AppBundle\Form\Image;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ImageType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
    	$builder
			->add('file', FileType::class, array(
				'label'=> false,
                'image_path' => 'webPath'
			))
    	;
  	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'AppBundle\Entity\Image'
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
