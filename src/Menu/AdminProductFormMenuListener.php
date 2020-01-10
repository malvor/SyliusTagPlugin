<?php
declare(strict_types=1);

namespace Websnacks\SyliusTagPlugin\Menu;


use Sylius\Bundle\AdminBundle\Event\ProductMenuBuilderEvent;

final class AdminProductFormMenuListener
{
    public function addAdminMenuItems(ProductMenuBuilderEvent $event) : void
    {
        $menu = $event->getMenu();

        $menu->addChild('tags')
            ->setLabel('websnacks_sylius_tag_plugin.menu.admin.tags')
            ->setAttribute('template', '@WebsnacksSyliusTagPlugin/Admin/Product/Tab/_tags.html.twig')
            ->setLabelAttribute('icon', 'tag');
    }

}