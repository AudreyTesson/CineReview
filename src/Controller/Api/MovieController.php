<?php

namespace App\Controller\Api;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityNotFoundException;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/api/movies", name="app_api_movie_")
 */
class MovieController extends CoreApiController
{
    /**
     * list all movies
     * 
     * @Route("",name="browse", methods={"GET"})
     *
     * @param MovieRepository $movieRepository
     * @return JsonResponse
     */
    public function browse(MovieRepository $movieRepository): JsonResponse
    {
        $allMovies = $movieRepository->findAll();
        return $this->json200($allMovies, ["movie_browse"]);
    }
    /**
     * @Route("/{id}", name="read", requirements={"id"="\d+"}, methods={"GET"})
     */
    public function read($id,MovieRepository $movieRepository): JsonResponse
    {
        $movie = $movieRepository->find($id);
        if ($movie === null){
            return $this->json(
                [
                    "message" => "Ce film n'existe pas"
                ],
                Response::HTTP_NOT_FOUND
            );
        }
        return $this->json($movie, 200, [], 
            [
                "groups" => 
                [
                    "movie_read"
                ]
            ]);
    }

    /**
     * ajout de film
     *
     * @Route("",name="add", methods={"POST"})
     * 
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param MovieRepository $movieRepository
     * 
     * @ IsGranted("ROLE_ADMIN")
     */
    public function add(
        Request $request, 
        SerializerInterface $serializer, 
        MovieRepository $movieRepository,
        ValidatorInterface $validatorInterface)
    {
        $jsonContent = $request->getContent();
        
        try {
            $movie = $serializer->deserialize($jsonContent, Movie::class, 'json');
        } catch (EntityNotFoundException $e){
            return $this->json("Denormalisation : ". $e->getMessage(), Response::HTTP_BAD_REQUEST);
        } catch (Exception $exception){
            return $this->json("JSON Invalide : " . $exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        $errors = $validatorInterface->validate($movie);
        if (count($errors) > 0) {
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $movieRepository->add($movie, true);

        return $this->json($movie, Response::HTTP_CREATED, [], ["groups"=>["movie_read"]]);
    }
}
