<?php
declare(strict_types=1);

namespace Websnacks\SyliusTagPlugin\Repository;

use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Websnacks\SyliusTagPlugin\Entity\TagInterface;
use Doctrine\ORM\QueryBuilder;

/**
 * Interface TagRepositoryInterface
 * @package Websnacks\SyliusTagPlugin\Repository
 */
interface TagRepositoryInterface extends RepositoryInterface
{

    public function findByEnabledQueryBuilder(?ChannelInterface $channel, ?ProductInterface $product): QueryBuilder;

    /**
     * @param ChannelInterface $channel
     * @return array
     */
    public function findByChannel(ChannelInterface $channel): array;

    /**
     * @param string $slug
     * @return null|TagInterface
     */
    public function findOneBySlug(string $slug): ?TagInterface;

    /**
     * @param ProductInterface $product
     * @return array
     */
    public function findByProduct(ProductInterface $product): array;
}