<?php

namespace App\Controller\Api;

use App\Entity\Genre;
use App\Repository\GenreRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api/genres", name="app_api_genres_")
 */
class GenreController extends CoreApiController
{
    /**
     * @Route("", name="browse", methods={"GET"})
     */
    public function browse(GenreRepository $genreRepository): JsonResponse
    {
        $allGenres = $genreRepository->findAll();

        return $this->json200($allGenres, ["genre_browse"]);
    }
    
    /**
     * @Route("/{id}", name="read", requirements={"id"="\d+"}, methods={"GET"})
     */
    public function read($id,GenreRepository $genreRepository): JsonResponse
    {
        $genre = $genreRepository->find($id);
        if ($genre === null){
            return $this->json(
                [
                    "message" => "Ce genre n'existe pas"
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        return $this->json200($genre,
                [
                    "genre_read",
                    "movie_browse"
                ]
            );
    }

    /**
     * ajout de genre
     *
     * @Route("",name="add", methods={"POST"})
     * 
     * @return JsonResponse
     */
    public function add(Request $request, SerializerInterface $serializerInterface, GenreRepository $genreRepository)
    {
        $jsonContent = $request->getContent();

        /** @var Genre $newGenre */
        $newGenre = $serializerInterface->deserialize(
            $jsonContent,
            Genre::class,
            'json'
        );

        $genreRepository->add($newGenre, true);

        // TODO : un peu d'UX : on renvoit le bon code de statut : 201
        return $this->json(
            $newGenre,
            Response::HTTP_CREATED,
            [],
            [
                "groups" =>
                [
                    "genre_read",
                    "movie_browse"
                ]
            ]
        );
    }

    /**
     * edit genre
     *
     * @Route("/{id}",name="edit", requirements={"id"="\d+"}, methods={"PUT", "PATCH"})
     * 
     * @param Request $request la requete
     * @param SerializerInterface $serializerInterface
     * @param GenreRepository $genreRepository
     */
    public function edit($id, Request $request, SerializerInterface $serializerInterface, GenreRepository $genreRepository)
    {
        $jsonContent = $request->getContent();
        $genre = $genreRepository->find($id);
        $serializerInterface->deserialize(
            $jsonContent,
            Genre::class,
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE => $genre]
        );
        $genreRepository->add($genre, true);

        return $this->json($genre,Response::HTTP_OK, [], ["groups"=>["genre_read","movie_browse"]]);
    }

    /**
     * delete genre
     * Merci Audrey
     *
     * @Route("/{id}",name="delete", requirements={"id"="\d+"}, methods={"DELETE"})
     */
    public function delete($id, GenreRepository $genreRepository)
    {
        $genre = $genreRepository->find($id);
        $genreRepository->remove($genre, true);

        return $this->json(null,Response::HTTP_NO_CONTENT);
    }
}
