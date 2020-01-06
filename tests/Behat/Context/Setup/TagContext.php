<?php
declare(strict_types=1);

namespace Tests\Websnacks\SyliusTagPlugin\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManagerInterface;
use Websnacks\SyliusTagPlugin\Entity\TagInterface;
use Websnacks\SyliusTagPlugin\Repository\TagRepositoryInterface;
use Sylius\Behat\Service\SharedStorageInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

class TagContext
{
    /** @var SharedStorageInterface */
    private $sharedStorage;

    /** @var FactoryInterface */
    private $tagFactory;

    /** @var TagRepositoryInterface */
    private $tagRepository;

    /** @var ProductRepositoryInterface */
    private $productRepository;

    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(
        SharedStorageInterface $sharedStorage,
        FactoryInterface $tagFactory,
        TagRepositoryInterface $vendorRepository,
        ProductRepositoryInterface $productRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->sharedStorage = $sharedStorage;
        $this->tagFactory = $tagFactory;
        $this->tagRepository = $vendorRepository;
        $this->productRepository = $productRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @param int $quantity
     * @Given the store has( also) :quantity tags
     */
    public function theStoreHasTags(int $quantity): void
    {
        for ($i = 0; $i <= $quantity; $i++) {
            $this->saveTag($this->createTag('Test'. $i));
        }
    }

    private function createTag(string $name): TagInterface
    {
        /** @var ChannelInterface $channel */
        $channel = $this->sharedStorage->get('channel');

        /** @var TagInterface $tag */
        $tag = $this->tagFactory->createNew();
        $tag->setName($name);
        $tag->setSlug(strtolower($name));
        $tag->setCurrentLocale('en_US');
        $tag->setFallbackLocale('en_US');
        $tag->addChannel($channel);

        return $tag;
    }

    private function saveTag(TagInterface $tag): void
    {
        $this->tagRepository->add($tag);
    }
}