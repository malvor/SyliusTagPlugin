<?php
declare(strict_types=1);

namespace Websnacks\SyliusTagPlugin\Entity;

use Doctrine\Common\Collections\Collection;

/**
 * Class TagTrait
 * @package Websnacks\SyliusTagPlugin\Entity
 */
trait TagTrait
{

    /** @var Collection */
    protected $tags;

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

}