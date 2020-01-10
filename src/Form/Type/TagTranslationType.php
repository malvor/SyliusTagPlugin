<?php
declare(strict_types=1);

namespace Websnacks\SyliusTagPlugin\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\FormBuilderInterface;

final class TagTranslationType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('name', TextType::class, [
                'label' => 'websnacks_sylius_tag_plugin.form.tag.name'
            ]);
    }
}