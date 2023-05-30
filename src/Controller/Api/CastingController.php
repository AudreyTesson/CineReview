<?php

namespace App\Controller\Api;

use App\Repository\CastingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/castings", name="app_api_castings")
 */
class CastingController extends AbstractController
{
    /**
     * @Route("/", name="browse", methods={"GET"})
     */
    public function index(CastingRepository $castingRepository): JsonResponse
    {
        $allCastings = $castingRepository->findAll();

        return $this->json([
            $allCastings,
            200,
            [],
            [
                'groups' => 
                [
                    'casting_browse'
                ]
            ]
        ]);
    }
}
