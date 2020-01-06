<?php
declare(strict_types=1);

namespace Websnacks\SyliusTagPlugin\Entity;

use Sylius\Component\Resource\Model\AbstractTranslation;
use Sylius\Component\Resource\Model\TimestampableTrait;

class TagTranslation extends AbstractTranslation implements TagTranslationInterface
{
    use TimestampableTrait;

    /** @var int|null */
    private $id;

    /** @var string|null */
    private $name;

    public function __construct()
    {
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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }
}