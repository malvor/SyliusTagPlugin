<?php

declare(strict_types=1);

namespace Websnacks\SyliusTagPlugin;

use Sylius\Bundle\CoreBundle\Application\SyliusPluginTrait;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class WebsnacksSyliusTagPlugin extends Bundle
{
    use SyliusPluginTrait;
}
