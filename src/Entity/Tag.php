<?php
declare(strict_types=1);

namespace Websnacks\SyliusTagPlugin\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Resource\Model\TranslatableTrait;
use Sylius\Component\Resource\Model\TimestampableTrait;
use Sylius\Component\Resource\Model\ToggleableTrait;
use Sylius\Component\Resource\Model\TranslationInterface;

/**
 * Class Tag
 * @package Websnacks\SyliusTagPlugin\Entity
 */
class Tag implements TagInterface
{
    use TranslatableTrait {
        __construct as private initializeTranslationsCollection;
        getTranslation as private doGetTranslation;
    }
    use TimestampableTrait;
    use ToggleableTrait;

    /** @var int|null */
    private $id;

    /** @var string|null */
    private $slug;

    /** @var Collection|ChannelInterface[] */
    protected $channels;

    /** @var Collection|ProductInterface[] */
    protected $products;

    public function __construct()
    {
        $this->initializeTranslationsCollection();

        $this->channels = new ArrayCollection();
        $this->products = new ArrayCollection();
        $this->createdAt = new \DateTime();
    }

    /**
     * {@inheritdoc}
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * {@inheritdoc}
     */
    public function setSlug(?string $slug): void
    {
        $this->slug = $slug;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        /** @var TagTranslationInterface $tagTranslation */
        $tagTranslation = $this->getTranslation();

        return $tagTranslation->getName();
    }

    /**
     * {@inheritdoc}
     */
    public function setName(?string $name): void
    {
        /** @var TagTranslationInterface $tagTranslation */
        $tagTranslation = $this->getTranslation();

        $tagTranslation->setName($name);
    }

    /**
     * {@inheritdoc}
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    /**
     * {@inheritdoc}
     */
    public function hasProduct(ProductInterface $product): bool
    {
        return $this->products->contains($product);
    }

    /**
     * {@inheritdoc}
     */
    public function addProduct(ProductInterface $product): void
    {
        if (false === $this->hasProduct($product)) {
            $this->products->add($product);
            if ($product instanceof TagsAwareInterface) {
                $product->addTag($this);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeProduct(ProductInterface $product): void
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);

            if ($product instanceof TagsAwareInterface) {
                $product->removeTag($this);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getChannels(): Collection
    {
        return $this->channels;
    }

    /**
     * {@inheritdoc}
     */
    public function hasChannel(ChannelInterface $channel): bool
    {
        return $this->channels->contains($channel);
    }

    /**
     * {@inheritdoc}
     */
    public function addChannel(ChannelInterface $channel): void
    {
        if (false === $this->hasChannel($channel)) {
            $this->channels->add($channel);

            if ($channel instanceof TagsAwareInterface) {
                $channel->addTag($this);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeChannel(ChannelInterface $channel): void
    {
        if ($this->hasChannel($channel)) {
            $this->channels->removeElement($channel);

            if ($channel instanceof TagsAwareInterface) {
                $channel->removeTag($this);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function createTranslation(): TranslationInterface
    {
        return new TagTranslation();
    }
}