<?php
declare(strict_types=1);

namespace Websnacks\SyliusTagPlugin\Menu;

use Knp\Menu\ItemInterface;
use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class AdminMenuListener
{
    public function addAdminMenuItems(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();

        /** @var ItemInterface $item */
        $item = $menu->getChild('catalog');
        if (null === $item) {
            $item = $menu;
        }

        $item->addChild('tags', ['route' => 'websnacks_sylius_tag_plugin_admin_tag_index'])
            ->setLabel('websnacks_sylius_tag_plugin.menu.admin.tags')
            ->setLabelAttribute('icon', 'tag');
    }
}