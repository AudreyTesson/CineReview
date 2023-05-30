<?php

namespace App\Controller\Front;

use App\Models\MovieModel;
use App\Repository\CastingRepository;
use App\Repository\GenreRepository;
use App\Repository\MovieRepository;
use App\Repository\ReviewRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class MainController extends AbstractController
{
    /**
     * page par défaut
     *
     * @Route("/", name="default", methods={"GET", "POST"})
     * 
     * @return Response
     */
    public function home(Request $request, MovieRepository $movieRepository, PaginatorInterface $paginatorInterface): Response
    {
        $allMovies = $movieRepository->findAll();

        $allMovies = $paginatorInterface->paginate($allMovies, $request->query->getInt('page', 1),5);

        $twigResponse = $this->render("front/main/home.html.twig",
        [
            "movieList" => $allMovies
        ]);
        
        return $twigResponse;
    }

    /**
     * affichage des détails d'un film
     *
     * @Route("/movies/{id}", name="app_front_movie_show", requirements={"id":"\d+"} ,methods={"GET"})
     * 
     * @return Response
     */
    public function show($id, 
    MovieRepository $movieRepository, 
    CastingRepository $castingRepository,
    ReviewRepository $reviewRepository
    ): Response
    {
        $movie = $movieRepository->find($id);
        if ($movie === null) {
            throw $this->createNotFoundException("Ce film n'existe pas");
        }

        $allCastingFromMovie = $castingRepository->findBy(
            [
                "movie" => $movie
            ],
            [
                "creditOrder" => "ASC"
            ]
        );

        $allReviews = $reviewRepository->findBy(
            [
                "movie" => $movie
            ],
            [
                "rating" => "DESC"
            ]
        );

        $twigResponse = $this->render("front/main/show.html.twig",
        [
            "movieId" => $id,
            "movieForTwig" => $movie,
            "allCastingFromBDD" => $allCastingFromMovie,
            "allReviewFromBDD" => $allReviews
        ]);
        

        return $twigResponse;
    }

    /**
     * la liste de résultat de recherche
     *
     * @Route("/search", name="app_front_movie_search")
     * 
     * @return Response
     */
    public function search(MovieRepository $movieRepository,
    GenreRepository $genreRepository,
    Request $request,
    PaginatorInterface $paginatorInterface): Response
    {
        $genres = $genreRepository->findAll();

        $search = $request->query->get('search', "");
        $movies = $movieRepository->findBySearch($search);

        $movies = $paginatorInterface->paginate($movies, $request->query->getInt('page', 1),5);

        return $this->render("front/main/search.html.twig",
            [
                'movieSearch' => $movies,
                'genreList' => $genres,
            ]
        );
    }

    /**
     * page show affiche les films par genre 
     *
     * @Route("/genre/{id}", name="app_front_genre_show", methods={"GET"}, requirements={"id"="\d+"})
     *
     * @return Response
     */
    public function genreShow($id, 
        GenreRepository $genreRepository, 
        MovieRepository $movieRepository,
        Request $request,
        PaginatorInterface $paginatorInterface): Response
    {
        $genreById = $genreRepository->find($id);
        $movieGenre = $movieRepository->findByGenre($id);
        $genreList = $genreRepository->findAll();

        if ($movieGenre === null) {
            throw $this->createNotFoundException("Ce genre n'a pas encore de films");
        }

        if ($genreById === null) {
            throw $this->createNotFoundException("Ce genre n'existe pas");
        }

        if ($genreList === null) {
            throw $this->createNotFoundException("Cette liste ne contient pas de genres");
        }

        $movieGenre = $paginatorInterface->paginate($movieGenre, $request->query->getInt('page', 1),5);
        
        $twigResponse = $this->render("front/main/genre.html.twig",
        [
            "genreList" => $genreList,
            'movieGenre' => $movieGenre,
            'genreById' => $genreById
        ]);
        
        return $twigResponse;
    }
}