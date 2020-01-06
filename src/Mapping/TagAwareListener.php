<?php
declare(strict_types=1);

namespace Websnacks\SyliusTagPlugin\Mapping;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Metadata\RegistryInterface;
use Websnacks\SyliusTagPlugin\Entity\TagInterface;
use Websnacks\SyliusTagPlugin\Entity\TagsAwareInterface;
use Sylius\Component\Core\Model\Channel;
use Sylius\Component\Core\Model\Product;

/**
 * Class TagAwareListener
 * @package Websnacks\SyliusTagPlugin\Mapping
 */
final class TagAwareListener implements EventSubscriber
{
    /** @var RegistryInterface */
    private $resourceMetadataRegistry;

    /** @var string */
    private $tagClass;

    /** @var string */
    private $productClass;

    /** @var string */
    private $channelClass;

    /**
     * TagAwareListener constructor.
     * @param RegistryInterface $resourceMetadataRegistry
     * @param string $tagClass
     * @param string $productClass
     * @param string $channelClass
     */
    public function __construct(
        RegistryInterface $resourceMetadataRegistry,
        string $tagClass,
        string $productClass,
        string $channelClass
    ) {
        $this->resourceMetadataRegistry = $resourceMetadataRegistry;
        $this->tagClass = $tagClass;
        $this->productClass = $productClass;
        $this->channelClass = $channelClass;
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents(): array
    {
        return [
            Events::loadClassMetadata,
        ];
    }

    /**
     * @param LoadClassMetadataEventArgs $eventArgs
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs): void
    {
        $classMetadata = $eventArgs->getClassMetadata();
        $reflection = $classMetadata->reflClass;

        if (!$reflection instanceof \ReflectionClass || $reflection->isAbstract()) {
            return;
        }

        if (
            $reflection->implementsInterface(ProductInterface::class) &&
            $reflection->implementsInterface(TagsAwareInterface::class)
        ) {
            $this->mapTagsAware($classMetadata, 'product_id', 'products');
        }

        if (
            $reflection->implementsInterface(ChannelInterface::class) &&
            $reflection->implementsInterface(TagsAwareInterface::class)
        ) {
            $this->mapTagsAware($classMetadata, 'channel_id', 'channels');
        }

        if ($reflection->implementsInterface(TagInterface::class) &&
            !$classMetadata->isMappedSuperclass
        ) {
            $this->mapTag($classMetadata);
        }
    }

    /**
     * @param ClassMetadata $metadata
     * @param string $joinColumn
     * @param string $inversedBy
     */
    private function mapTagsAware(ClassMetadata $metadata, string $joinColumn, string $inversedBy): void
    {
        try {
            $tagMetadata = $this->resourceMetadataRegistry->getByClass($this->tagClass);
        } catch (\InvalidArgumentException $exception) {
            return;
        }

        if (false === $metadata->hasAssociation('tags')) {
            $metadata->mapManyToMany([
                'fieldName' => 'tags',
                'targetEntity' => $tagMetadata->getClass('mode'),
                'inversedBy' => $inversedBy,
                'joinTable' => [
                    'name' => 'websnacks_tag_'.$inversedBy,
                    'joinColumns' => [[
                        'name' => $joinColumn,
                        'referencedColumnName' => 'id'
                    ]],
                    'inverseJoinColumns' => [[
                        'name' => 'tag_id',
                        'referencedColumnName' => 'id'
                    ]],
                ]
            ]);
        }
    }

    /**
     * @param ClassMetadata $metadata
     */
    private function mapTag(ClassMetadata $metadata): void
    {
        try {
            $productMetadata = $this->resourceMetadataRegistry->getByClass($this->productClass);
            $channelMetadata = $this->resourceMetadataRegistry->getByClass($this->channelClass);
        } catch (\InvalidArgumentException $exception) {
            return;
        }
        if (!$metadata->hasAssociation('products')) {
            $productConfig = [
                'fieldName' => 'products',
                'targetEntity' => $productMetadata->getClass('model')
            ];
            if (Product::class != $this->productClass) {
                $productConfig = array_merge($productConfig, [
                    'mappedBy' => 'tag',
                ]);
            }
            $metadata->mapOneToMany($productConfig);
        }
        if (!$metadata->hasAssociation('channels')) {
            $channelConfig = [
                'fieldName' => 'channels',
                'targetEntity' => $channelMetadata->getClass('model')
            ];
            if (Channel::class != $this->channelClass) {
                $channelConfig = array_merge($channelConfig, [
                    'mappedBy' => 'tag',
                ]);
            }
            $metadata->mapManyToMany($channelConfig);
        }
    }
}