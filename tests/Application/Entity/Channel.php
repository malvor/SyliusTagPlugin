<?php
declare(strict_types=1);

namespace Tests\Websnacks\SyliusTagPlugin\Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Core\Model\Channel as BaseChannel;
use Websnacks\SyliusTagPlugin\Entity\TagsAwareInterface;
use Websnacks\SyliusTagPlugin\Entity\TagsTrait;

/**
 * @ORM\Table(name="sylius_channel")
 * @ORM\Entity
 */
class Channel extends BaseChannel implements TagsAwareInterface
{
    use TagsTrait {
        __construct as private initializeTagsCollection;
    }

    public function __construct()
    {
        parent::__construct();
        $this->initializeTagsCollection();
    }
}