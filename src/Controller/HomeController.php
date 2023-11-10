<?php

namespace App\Controller;

use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private $movieRepository;

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
}
