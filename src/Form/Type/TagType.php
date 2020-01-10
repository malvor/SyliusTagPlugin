<?php
declare(strict_types=1);

namespace Websnacks\SyliusTagPlugin\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\{
    TextType,
    CheckboxType
};
use Sylius\Bundle\ChannelBundle\Form\Type\ChannelChoiceType;
use Sylius\Bundle\ResourceBundle\Form\Type\{
    ResourceTranslationsType,
    AbstractResourceType
};
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class TagType extends AbstractResourceType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('slug', TextType::class, [
                'label' => 'websnacks_sylius_tag_plugin.form.tag.slug'
            ])
            ->add('translations', ResourceTranslationsType::class, [
                'entry_type' => TagTranslationType::class,
                'label' => 'websnacks_sylius_tag_plugin.form.tag.translations',
            ])
            ->add('enabled', CheckboxType::class, [
                'label' => 'sylius.ui.enabled',
            ])
            ->add('channels', ChannelChoiceType::class, [
                'required' => false,
                'multiple' => true,
                'expanded' => true,
                'label' => 'websnacks_sylius_tag_plugin.form.tag.channels',
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);
    }
}