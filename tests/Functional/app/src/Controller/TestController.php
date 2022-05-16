<?php

namespace Braunstetter\LocalizedRoutes\Tests\Functional\app\src\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class TestController extends AbstractController
{
    public function home(): JsonResponse
    {
        return $this->json([]);
    }

    public function index(): JsonResponse
    {
        return $this->json([]);
    }

    public function indexUnsupported(): JsonResponse
    {
        return $this->json([]);
    }
}