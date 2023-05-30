<?php

namespace App\Controller\Front;

use App\Entity\Movie;
use App\Models\MovieModel;
use App\Repository\MovieRepository;
use App\Services\FavoritesService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FavoritesController extends AbstractController
{
    /**
     * afficher le(s) film favoris
     * 
     * @Route("/favorites", name="app_front_favorites")
     */
    public function index(FavoritesService $favoritesService): Response    
    {
        $favorisList = $favoritesService->list();

        return $this->render('front/favorites/index.html.twig', 
        [
            "movies" => $favorisList
        ]);
    }

    /**
     * ajout un film en favoris
     *
     * @Route("/favorites/add/{id}", name="app_front_favorites_add", requirements={"id"="\d+"})
     * 
     * @return Response
     */
    public function add($id, MovieRepository $movieRepository, FavoritesService $favoritesService): Response
    {
        $movie = $movieRepository->find($id);

        if ($movie === null){ throw $this->createNotFoundException("ce film n'existe pas.");}

        $favoritesService->add($movie);

        return $this->redirectToRoute('app_front_favorites');
    }   

    /**
     * supression d'un favoris
     * 
     * @Route("/favorites/remove/{id}", name="app_front_favorites_remove", requirements={"id"="\d+"})
     *
     * @return Response
     */
    public function remove($id, FavoritesService $favoritesService, MovieRepository $movieRepository):Response
    {
        $movie = $movieRepository->find($id);
        if ($movie === null){ throw $this->createNotFoundException("ce film n'existe pas.");}
        
        $favoritesService->remove($movie);

        return $this->redirectToRoute('app_front_favorites');
    }

    /**
     * supprime tout les favoris
     *
     * @Route("favorites/clear", name="app_front_favorites_clear")
     * 
     * @param FavoritesService $favoritesService
     * @return Response
     */
    public function removeAll(FavoritesService $favoritesService): Response
    {
        $favoritesService->removeAll();

        return $this->redirectToRoute('app_front_favorites');

    }
}
