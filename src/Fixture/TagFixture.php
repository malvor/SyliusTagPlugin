<?php
declare(strict_types=1);

namespace Websnacks\SyliusTagPlugin\Fixture;

use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Websnacks\SyliusTagPlugin\Entity\TagInterface;
use Sylius\Bundle\FixturesBundle\Fixture\AbstractFixture;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Sylius\Component\Product\Repository\ProductRepositoryInterface;
use Sylius\Component\Locale\Model\LocaleInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Websnacks\SyliusTagPlugin\Entity\TagsAwareInterface;

final class TagFixture extends AbstractFixture
{
    /** @var ObjectManager */
    private $objectManager;

    /** @var FactoryInterface */
    private $tagFactory;

    /**  @var RepositoryInterface */
    private $vendorRepository;

    /** @var ChannelRepositoryInterface */
    private $channelRepository;

    /** @var RepositoryInterface */
    private $localeRepository;

    /** @var \Faker\Generator */
    private $faker;

    /** @var OptionsResolver */
    private $optionsResolver;

    /** @var ProductRepositoryInterface */
    private $productRepository;

    /**
     * TagFixture constructor.
     * @param ObjectManager $objectManager
     * @param FactoryInterface $tagFactory
     * @param RepositoryInterface $vendorRepository
     * @param ChannelRepositoryInterface $channelRepository
     * @param RepositoryInterface $localeRepository
     * @param ProductRepositoryInterface $productRepository\
     */
    public function __construct(
        ObjectManager $objectManager,
        FactoryInterface $tagFactory,
        RepositoryInterface $vendorRepository,
        ChannelRepositoryInterface $channelRepository,
        RepositoryInterface $localeRepository,
        ProductRepositoryInterface $productRepository
    ) {
        $this->objectManager = $objectManager;
        $this->tagFactory = $tagFactory;
        $this->vendorRepository = $vendorRepository;
        $this->channelRepository = $channelRepository;
        $this->localeRepository = $localeRepository;
        $this->productRepository = $productRepository;

        $this->faker = Factory::create();
        $this->optionsResolver = (new OptionsResolver())
            ->setRequired(['tags_per_product', 'tags_per_channel'])
            ->setAllowedTypes('tags_per_product', 'int')
            ->setAllowedTypes('tags_per_channel', 'int')
            ;
    }

    public function load(array $options): void
    {
        $options = $this->optionsResolver->resolve($options);

        $channels = $this->channelRepository->findAll();
        $tags = [];
        foreach ($channels as $channel) {
            for ($i = 1; $i < $options['tags_per_channel']; $i++) {
                /** @var TagInterface $tag */
                $tag = $this->tagFactory->createNew();
                $tag->setSlug($this->faker->slug);
                $tag->addChannel($channel);

                foreach ($this->getLocales() as $localeCode) {
                    $tag->setCurrentLocale($localeCode);
                    $tag->setFallbackLocale($localeCode);
                    $tag->setName($this->faker->name);
                }
                $tags[] = $tag;
                $this->objectManager->persist($tag);
            }
        }
        $products = $this->productRepository->findAll();
        $tagsPerProduct = $this->getProductTagsPartition($tags, count($products), $options['tags_per_product']);

        foreach ($tagsPerProduct as $key => $productTags) {
            foreach ($productTags as $tag) {
                $tag->addProduct($products[$key]);
            }
        }
        $this->objectManager->flush();
    }

    public function getName(): string
    {
        return 'tag';
    }

    protected function getProductTagsPartition(array $tagList, int $numberOfProducts, int $tagsPerProduct) : array
    {
        $numberOfTags = count($tagList);
        $requiredTags = $numberOfProducts * $tagsPerProduct;
        if ($numberOfTags < $requiredTags) {
            $i = 0;
            while (count($tagList) < $requiredTags) {
                $tagList[] = $tagList[$i];
                $i++;
                $numberOfTags++;
                if ($i >= $numberOfTags) {
                    $i = 0;
                }
            }
        }
        return array_chunk($tagList, $tagsPerProduct);
    }

    /**
     * {@inheritdoc}
     */
    protected function configureOptionsNode(ArrayNodeDefinition $optionsNode): void
    {
        $optionsNode
            ->children()
            ->integerNode('tags_per_channel')->isRequired()->min(1)->end()
            ->integerNode('tags_per_product')->isRequired()->min(1)->end()
        ;
    }

    /**
     * @return \Generator
     */
    private function getLocales(): \Generator
    {
        /** @var LocaleInterface[] $locales */
        $locales = $this->localeRepository->findAll();
        foreach ($locales as $locale) {
            yield $locale->getCode();
        }
    }

}