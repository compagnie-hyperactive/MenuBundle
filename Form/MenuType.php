<?php

namespace Lch\MenuBundle\Form;

use Lch\MenuBundle\DependencyInjection\Configuration;
use Lch\MenuBundle\Entity\Menu;
use Lch\TranslateBundle\EventListener\AddTranslationsFieldsEventSubscriber;
use Lch\TranslateBundle\Form\Type\LanguageType;
use Lch\TranslateBundle\Form\Type\TranslatedParentType;
use Lch\TranslateBundle\Utils\TranslationsHelper;
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

    /** @var TranslationsHelper $translationsHelper */
    protected $translationsHelper;

    /**
     * MenuType constructor.
     * @param TranslationsHelper $translationsHelper
     */
    public function __construct(TranslationsHelper $translationsHelper)
    {
        $this->translationsHelper = $translationsHelper;
    }


    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => static::ROOT_TRANSLATION_PATH . ".title.label",
                'attr' => [
                    'helper' => static::ROOT_TRANSLATION_PATH . ".title.helper",
                    'width' => 50
                ],
                'translation_domain' => 'LchMenuBundle'
            ])
            ->add('location', MenuLocationType::class, [
                'label' => static::ROOT_TRANSLATION_PATH . ".location.label",
                'translation_domain' => 'LchMenuBundle'
            ])
            ->add('menuItems', MenuTreeType::class, [
                'label' => static::ROOT_TRANSLATION_PATH . '.menu_items.label',
//                'allow_add' => true,
//                'allow_delete' => true,
                'attr' => array(
                    'helper' => static::ROOT_TRANSLATION_PATH . '.menu_items.helper'
                ),
                'translation_domain' => 'LchMenuBundle'
            ])
        ;

        if ($this->translationsHelper->isTranslationSystemEnabled()) {
            $builder
                ->add('language', LanguageType::class)
                ->add('translatedParent', TranslatedParentType::class, [
                    'class' => Menu::class
                ])
            ;
        }
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
