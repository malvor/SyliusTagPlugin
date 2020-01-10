<?php
declare(strict_types=1);

namespace Tests\Websnacks\SyliusTagPlugin\Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Core\Model\Product as BaseProduct;
use Websnacks\SyliusTagPlugin\Entity\TagsAwareInterface;
use Websnacks\SyliusTagPlugin\Entity\TagsTrait;

/**
 * @ORM\Table(name="sylius_product")
 * @ORM\Entity
 */
class Product extends BaseProduct implements TagsAwareInterface
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