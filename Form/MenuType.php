<?php

namespace Lch\MenuBundle\Form;

use Lch\MenuBundle\DependencyInjection\Configuration;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MenuType extends AbstractType
{
    const NAME = "lch_menu";
    const ROOT_TRANSLATION_PATH = "lch.menu.form.fields";

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
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $locations = $this->locations;

        $builder
            ->add('title', TextType::class, [
                'label' => static::ROOT_TRANSLATION_PATH . ".title.label",
                'attr' => [
                    'helper' => static::ROOT_TRANSLATION_PATH . ".title.helper",
                    'width' => 50
                ]
            ])
            ->add('location', ChoiceType::class, [
                'label' => static::ROOT_TRANSLATION_PATH . ".location.label",
                'multiple'  => false,
                'expanded'  => false,
                'choices'   => $this->locations,
                'choice_value' => function($location) {
                    return $location;
                },
                'choice_label' => function($key, $location) {
                    return $location;
                },
                'attr' => [
                    'helper' => static::ROOT_TRANSLATION_PATH . ".location.helper",
                    'width' => 50
                ]
            ])
            ->add('menuItems', MenuTreeType::class, [
                'label' => static::ROOT_TRANSLATION_PATH . '.menu_items.label',
                'attr' => array(
                    'helper' => static::ROOT_TRANSLATION_PATH . '.menu_items.helper'
                ),
            ])
        ;
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Lch\MenuBundle\Entity\Menu'
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return static::NAME;
    }
}
