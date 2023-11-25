<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private MovieRepository $movieRepository;

    public function __construct(MovieRepository $movieRepository, EntityManagerInterface $em)
    {
        $this->movieRepository = $movieRepository;
        $this->em = $em;
    }
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        $movies = $this->movieRepository->findAll();
        return $this->render('home/home.html.twig',[
            'movies'=>$movies
        ]);
    }
    #[Route('/search', name: 'search_movies', methods: ["GET"])]
    public function search(Request $request): JsonResponse
    {
        $searchTerm = $request->query->get('term');
        $movies = $this->movieRepository->searchByTerm($searchTerm);;


        return $this->json($movies);
    }
}
