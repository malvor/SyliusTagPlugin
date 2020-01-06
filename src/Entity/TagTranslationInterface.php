<?php
declare(strict_types=1);

namespace Websnacks\SyliusTagPlugin\Entity;

use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TimestampableInterface;
use Sylius\Component\Resource\Model\TranslationInterface;

/**
 * Interface TagTranslationInterface
 * @package Websnacks\SyliusTagPlugin\Entity
 */
interface TagTranslationInterface extends
    ResourceInterface,
    TranslationInterface,
    TimestampableInterface
{

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param null|string $name
     */
    public function setName(?string $name): void;
}