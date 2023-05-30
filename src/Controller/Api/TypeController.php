<?php

namespace App\Controller\Api;

use App\Repository\TypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/types", name="app_api_types_")
 */
class TypeController extends AbstractController
{
    /**
     * @Route("/", name="browse", methods={"GET"})
     */
    public function index(TypeRepository $typeRepository): JsonResponse
    {
        $allTypes = $typeRepository->findAll();

        return $this->json(
            $allTypes,
            200,
            [],
            [
            'groups' => 'type_browse',
        ]);
    }
}
