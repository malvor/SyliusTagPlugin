<?php
declare(strict_types=1);

namespace Websnacks\SyliusTagPlugin\Controller;

use Sylius\Component\Product\Generator\SlugGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class TagSlugController extends AbstractController
{
    /** @var SlugGeneratorInterface */
    private $slugGenerator;

    public function __construct(
        SlugGeneratorInterface $slugGenerator
    ) {
        $this->slugGenerator = $slugGenerator;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function generateAction(Request $request): Response
    {
        $name = $request->query->get('name');
        return new JsonResponse([
            'slug' => $this->slugGenerator->generate($name),
        ]);
    }
}