<?php
declare(strict_types=1);

namespace Websnacks\SyliusTagPlugin\Fixture;

use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Websnacks\SyliusTagPlugin\Entity\TagInterface;
use Sylius\Bundle\FixturesBundle\Fixture\AbstractFixture;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Locale\Model\LocaleInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\OptionsResolver\OptionsResolver;

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

    /**
     * TagFixture constructor.
     * @param ObjectManager $objectManager
     * @param FactoryInterface $tagFactory
     * @param RepositoryInterface $vendorRepository
     * @param ChannelRepositoryInterface $channelRepository
     * @param RepositoryInterface $localeRepository
     */
    public function __construct(
        ObjectManager $objectManager,
        FactoryInterface $tagFactory,
        RepositoryInterface $vendorRepository,
        ChannelRepositoryInterface $channelRepository,
        RepositoryInterface $localeRepository
    ) {
        $this->objectManager = $objectManager;
        $this->tagFactory = $tagFactory;
        $this->vendorRepository = $vendorRepository;
        $this->channelRepository = $channelRepository;
        $this->localeRepository = $localeRepository;

        $this->faker = Factory::create();
        $this->optionsResolver = (new OptionsResolver())
            ->setRequired('tags_per_channel')
            ->setAllowedTypes('tags_per_channel', 'int');
    }

    public function load(array $options): void
    {
        $options = $this->optionsResolver->resolve($options);

        $channels = $this->channelRepository->findAll();
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

                $this->objectManager->persist($tag);
            }
        }
        $this->objectManager->flush();
    }

    public function getName(): string
    {
        return 'tag';
    }

    /**
     * {@inheritdoc}
     */
    protected function configureOptionsNode(ArrayNodeDefinition $optionsNode): void
    {
        $optionsNode
            ->children()
            ->integerNode('tags_per_channel')->isRequired()->min(1)->end()
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