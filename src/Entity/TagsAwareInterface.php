<?php
declare(strict_types=1);

namespace Websnacks\SyliusTagPlugin\Entity;

use Doctrine\Common\Collections\Collection;

/**
 * Interface TagsAwareInterface
 * @package Websnacks\SyliusTagPlugin\Entity
 */
interface TagsAwareInterface
{
    /**
     * @param TagInterface $tag
     */
    public function addTag(TagInterface $tag): void;

    /**
     * @param TagInterface $tag
     * @return bool
     */
    public function hasTag(TagInterface $tag): bool;

    /**
     * @param TagInterface $tag
     */
    public function removeTag(TagInterface $tag): void;

    /**
     * @return Collection
     */
    public function getTags(): Collection;

    /**
     * @param Collection $tags
     */
    public function setTags(Collection $tags): void;
}