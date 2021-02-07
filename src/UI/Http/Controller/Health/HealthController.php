<?php

declare(strict_types=1);

namespace Bodas\UI\Http\Controller\Health;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

final class HealthController extends AbstractController
{
    public function __invoke(): JsonResponse
    {
        return new JsonResponse(['status' => 'running'], JsonResponse::HTTP_OK);
    }
}
