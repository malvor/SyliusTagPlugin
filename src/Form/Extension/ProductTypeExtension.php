<?php
declare(strict_types=1);

namespace Websnacks\SyliusTagPlugin\Form\Extension;

use Sylius\Bundle\ProductBundle\Form\Type\ProductType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Websnacks\SyliusTagPlugin\Form\Type\TagType;

final class ProductTypeExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
//        $builder
//            ->add('tags', TagType::class, [
//                'label' => false,
//
//            ])
//        ;
    }

    /**
     * @inheritdoc
     */
    public function getExtendedTypes()
    {
        return [ProductType::class];
    }
}