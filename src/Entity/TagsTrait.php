<?php
declare(strict_types=1);

namespace Websnacks\SyliusTagPlugin\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class TagTrait
 * @package Websnacks\SyliusTagPlugin\Entity
 */
trait TagsTrait
{

    /** @var Collection */
    protected $tags;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    /**
     * @return Collection
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    /**
     * @param Collection $tags
     */
    public function setTags(Collection $tags): void
    {
        $this->tags = $tags;
    }

    /**
     * @param TagInterface $tag
     * @return bool
     */
    public function hasTag(TagInterface $tag): bool
    {
        return $this->tags->contains($tag);
    }

    /**
     * @param TagInterface $tag
     */
    public function addTag(TagInterface $tag): void
    {
        if (false === $this->hasTag($tag)) {
            $this->tags->add($tag);
        }
    }

    /**
     * @param TagInterface $tag
     */
    public function removeTag(TagInterface $tag): void
    {
        if ($this->hasTag($tag)) {
            $this->tags->removeElement($tag);
        }
    }

}