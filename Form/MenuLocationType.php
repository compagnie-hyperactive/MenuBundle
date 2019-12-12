<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 25/05/18
 * Time: 10:22
 */

namespace Lch\MenuBundle\Form;


use Lch\MenuBundle\DependencyInjection\Configuration;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MenuLocationType extends AbstractType {
	const NAME = 'lch_menu_location';

	/**
	 * @var array $locations
	 */
	private $locations;

	/**
	 * MenuType constructor.
	 * @param array $locations
	 */
	public function __construct(array $locations) {
		$this->locations = [];
		foreach($locations as $key => $location) {
			$this->locations[$key] = $location[Configuration::TITLE];
		}
		$this->locations = array_flip($this->locations);
	}


	/**
	 * @inheritdoc
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
        $resolver->setDefaults([
	        'multiple'  => false,
	        'expanded'  => false,
	        'choices'   => $this->locations,
	        'choice_value' => function($location) {
		        return $location;
	        },
	        'choice_label' => function($key, $location) {
		        return $location;
	        },
        ]);
	}

	/**
	 * @inheritdoc
	 */
	public function getParent()
	{
		return ChoiceType::class;
	}

	/**
	 * @inheritdoc
	 */
	public function getBlockPrefix()
	{
		return static::NAME;
	}
}