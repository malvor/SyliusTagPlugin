<?php
declare(strict_types=1);

namespace Websnacks\SyliusTagPlugin\Repository;

use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Websnacks\SyliusTagPlugin\Entity\TagInterface;

class TagRepository extends EntityRepository implements TagRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function findByEnabledQueryBuilder(?ChannelInterface $channel = null, ?ProductInterface $product = null): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('t')
            ->andWhere('t.enabled = :enabled')
            ->setParameter('enabled', true)
        ;
        if ($channel) {
            $queryBuilder->innerJoin('t.channels', 'channel')
                ->andWhere('channel = :channel')
                ->setParameter('channel', $channel)
            ;
        }

        if ($product) {
            $queryBuilder->innerJoin('t.products', 'product')
                ->andWhere('product = :product')
                ->setParameter('product', $product)
            ;
        }
        return $queryBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function findByChannel(ChannelInterface $channel): array
    {
        return $this->findByEnabledQueryBuilder($channel)->getQuery()->getResult();
    }

    /**
     * {@inheritdoc}
     * @throws
     */
    public function findOneBySlug(string $slug): ?TagInterface
    {
        $tag = $this->createQueryBuilder('t')
            ->andWhere('t.slug = :slug')
            ->andWhere('t.enabled = :enabled')
            ->setParameters([
                'slug' => $slug,
                'enabled' => true
            ])
            ->getQuery()
            ->getOneOrNullResult();

        return $tag;
    }

    /**
     * {@inheritdoc}
     */
    public function findByProduct(ProductInterface $product): array
    {
        return $this->findByEnabledQueryBuilder(null, $product)->getQuery()->getResult();
    }

}