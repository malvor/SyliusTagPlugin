<?php
declare(strict_types=1);

namespace Websnacks\SyliusTagPlugin\Entity;

use Doctrine\Common\Collections\Collection;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\SlugAwareInterface;
use Sylius\Component\Resource\Model\TranslatableInterface;
use Sylius\Component\Resource\Model\ToggleableInterface;
use Sylius\Component\Resource\Model\TranslationInterface;
use Sylius\Component\Channel\Model\ChannelsAwareInterface;
use Sylius\Component\Resource\Model\TimestampableInterface;

/**
 * Interface TagInterface
 * @package Websnacks\SyliusTagPlugin\Entity
 */
interface TagInterface extends
    ResourceInterface,
    SlugAwareInterface,
    TranslatableInterface,
    ToggleableInterface,
    TimestampableInterface,
    ChannelsAwareInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param string $name
     */
    public function setName(?string $name): void;

    /**
     * @return Collection
     */
    public function getProducts(): Collection;

    /**
     * @param ProductInterface $product
     * @return bool
     */
    public function hasProduct(ProductInterface $product): bool;

    /**
     * @param ProductInterface $product
     */
    public function addProduct(ProductInterface $product): void;

    /**
     * @param ProductInterface $product
     */
    public function removeProduct(ProductInterface $product): void;

    /**
     * @param null|string $locale
     * @return TranslationInterface
     */
    public function getTranslation(?string $locale = null): TranslationInterface;
}