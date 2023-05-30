<?php

namespace App\Controller\Api;

use App\Repository\ReviewRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/reviews", name="app_api_review_")
 */
class ReviewController extends AbstractController
{
    /**
     * @Route("/{id}", name="read", requirements={"id"="\d+"}, methods={"GET"})
     */
    public function index($id, ReviewRepository $reviewRepository): JsonResponse
    {
        $review = $reviewRepository->find($id);

        return $this->json($review, 200, [],
            [
            'groups' => 'review_read',
        ]);
    }
}
