<?php

namespace App\Controller;

use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;


class APIController extends AbstractFOSRestController
{
    private MovieRepository $movieRepository;
    private EntityManagerInterface $em;

    public function __construct(MovieRepository $movieRepository, EntityManagerInterface $em)
    {
        $this->movieRepository = $movieRepository;
        $this->em = $em;
    }
    #[Rest\Get('/api/v1/movies', name: 'movie_list')]
    public function getMovies(): Response
    {
        // Fetch all movies from the database
        $movies = $this->movieRepository->findAll();

        // Check if any movies were found
        if (!$movies) {
            // No movies found, return a 204 No Content response
            return $this->handleView(View::create([], Response::HTTP_NO_CONTENT));
        }

        // Movies found, create a view with the movies data and set the status code to 200 OK
        $view = View::create($movies, Response::HTTP_OK);
        $view->setFormat('json');
        // Return the response
        return $this->handleView($view);

    }
    #[Rest\Get('/api/v1/movies/{id}', name: 'movie_get')]
    public function getMovie($id):Response
    {
        $movie = $this->movieRepository->find($id);

        if (!$movie){
            return $this->handleView(View::create(['message' => 'Movie not found'], Response::HTTP_NOT_FOUND));
        }

        $view = View::create($movie, Response::HTTP_OK);
        $view->setFormat('json');

        return $this->handleView($view);
    }
}
