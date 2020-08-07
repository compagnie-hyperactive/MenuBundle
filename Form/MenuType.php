<?php

namespace Lch\MenuBundle\Form;

use Doctrine\ORM\QueryBuilder;
use Lch\MenuBundle\Entity\Menu;
use Lch\MenuBundle\Repository\MenuRepository;
use Lch\TranslateBundle\Form\Type\LanguageType;
use Lch\TranslateBundle\Form\Type\TranslatedParentType;
use Lch\TranslateBundle\Utils\TranslationsHelper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MenuType extends AbstractType
{
    const NAME = "lch_menu";
    const ROOT_TRANSLATION_PATH = "lch.menu.form.fields";

    /** @var TranslationsHelper $translationsHelper */
    protected $translationsHelper;

    /** @var string $defaultLocale */
    protected $defaultLocale;

    /**
     * MenuType constructor.
     *
     * @param TranslationsHelper $translationsHelper
     */
    public function __construct(TranslationsHelper $translationsHelper, string $defaultLocale)
    {
        $this->translationsHelper = $translationsHelper;
        $this->defaultLocale = $defaultLocale;
    }


    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label'              => static::ROOT_TRANSLATION_PATH . ".title.label",
                'attr'               => [
                    'helper' => static::ROOT_TRANSLATION_PATH . ".title.helper",
                    'width'  => 50
                ],
                'translation_domain' => 'LchMenuBundle'
            ])
            ->add('location', MenuLocationType::class, [
                'label'              => static::ROOT_TRANSLATION_PATH . ".location.label",
                'translation_domain' => 'LchMenuBundle'
            ])
            ->add('menuItems', HiddenType::class, [
                'label'              => static::ROOT_TRANSLATION_PATH . '.menu_items.label',
                'attr'               => array(
                    'helper' => static::ROOT_TRANSLATION_PATH . '.menu_items.helper'
                ),
                'translation_domain' => 'LchMenuBundle',
                'block_prefix' => static::NAME . '_tree'
            ]);

        if ($this->translationsHelper->isTranslationSystemEnabled()) {
            $builder
                ->add('language', LanguageType::class)
                ->add('translatedParent', TranslatedParentType::class, [
                    'class'         => Menu::class,
                    'choice_label'  => function (Menu $menu) {
                        return "{$menu->getTitle()} ({$menu->getLocation()})";
                    },
                    'query_builder' => function (MenuRepository $r) {
                        /** @var QueryBuilder $qb */
                        $qb = $r->createQueryBuilder('m');

                        return $qb
                            ->where('m.language = :language')
                            ->setParameter('language', $this->defaultLocale)
                            ;
                    },
                    'attr'          => [
                        'helper' => 'admin.form.translated_parent_helper_label'
                    ]
                ]);
        } else {
            $builder->addEventListener(FormEvents::POST_SUBMIT, [$this, 'setDefaultLanguage']);
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

    public function setDefaultLanguage(FormEvent $event)
    {
        /** @var Menu $data */
        $data = $event->getData();
        $data->setLanguage($this->defaultLocale);
    }
}
