<?php
declare(strict_types=1);

namespace Tests\Websnacks\SyliusTagPlugin\Behat\Page\Shop\Tag;

use FriendsOfBehat\PageObjectExtension\Page\SymfonyPage;

final class IndexPage extends SymfonyPage implements IndexPageInterface
{
    /**
     * @inheritdoc
     */
    public function hasPagesNumber(int $pagesNumber): bool
    {
        $tagNumbersOnPage = count($this->getElement('tags')->findAll('css', '.tag'));
        return $pagesNumber === $tagNumbersOnPage;
    }

    /**
     * @inheritdoc
     */
    public function getRouteName(): string
    {
        return 'websnacks_sylius_tag_plugin_shop_tag_index';
    }

    /**
     * @inheritdoc
     */
    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'tags' => '#websnacks-tags'
        ]);
    }

}