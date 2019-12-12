<?php

namespace Lch\MenuBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MenuItemType extends AbstractType
{
    const NAME = "lch_menu_item";
    const ROOT_TRANSLATION_PATH = "lch.menu.item.form.fields";

    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => static::ROOT_TRANSLATION_PATH . ".title.label"
            ])
            ->add('target', UrlType::class, [
                'label' => static::ROOT_TRANSLATION_PATH . ".target.label"
            ]);


        if($options['enablable']) {
            $builder->add('enabled', CheckboxType::class, [
                'label' => static::ROOT_TRANSLATION_PATH . ".enabled.label",
                'required' => false
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
           'data_class' => 'Lch\MenuBundle\Entity\MenuItem',
           'enablable'  => true
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
