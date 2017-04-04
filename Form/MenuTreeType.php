<?php

namespace Lch\MenuBundle\Form;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MenuTreeType extends AbstractType
{
    const NAME = 'lch_menu_tree';

    /**
     * @var ObjectManager
     */
    protected $manager;

    /**
     * MenuTreeType constructor.
     * @param ObjectManager $manager
     */
    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
//        $jsonToMenuItemTransformer = new JsonToMenuTreeTransformer($this->manager);
//        $builder->addViewTransformer($jsonToMenuItemTransformer);
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {

    }

    /**
     * @inheritdoc
     */
    public function getParent()
    {
//        return CollectionType::class;
        return HiddenType::class;
    }

    /**
     * @inheritdoc
     */
    public function getBlockPrefix()
    {
        return static::NAME;
    }
}
