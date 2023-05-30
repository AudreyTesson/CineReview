<?php

namespace App\Services;

use App\Entity\Movie;
use Exception;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class FavoritesService
{
    
    /**
     * @var RequestStack
     */
    private $request;

    public function __construct(RequestStack $request)
    {
        $this->request = $request;
    }

    /**
    * get current session
    */
    public function getSession()
    {
        return $this->request->getSession();
    }

    public function list()
    {
        $favoris = $this->getSession()->get("favoris", []);

        return $favoris;
    }

    public function add(Movie $movie)
    {
        $favorisList = $this->getSession()->get("favoris", []);
        $favorisList[$movie->getId()] = $movie;

        $this->getSession()->set("favoris", $favorisList);
    }

    public function remove(Movie $movie)
    {
        $favorisList = $this->getSession()->get("favoris", []);

        if (array_key_exists($movie->getId(), $favorisList)){
            unset($favorisList[$movie->getId()]);
            $this->getSession()->set("favoris", $favorisList);
        }
    }

    public function removeAll()
    {
        $this->getSession()->set("favoris", []);
        $this->getSession()->remove("favoris");
    }

}