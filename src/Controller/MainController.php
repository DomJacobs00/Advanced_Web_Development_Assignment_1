<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Form\MovieAddType;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    private $em;

    private $movieRepository;

    public function __construct(MovieRepository $movieRepository, EntityManagerInterface $em)
    {
        $this->movieRepository = $movieRepository;
        $this->em = $em;
    }

    #[Route('/home', name: 'home', methods: ['GET'])]
    public function index(): Response
    {
        $movies = $this->movieRepository->findAll();
        return $this->render('home.html.twig',[
            'movies'=>$movies
        ]);
    }
    #[Route('/addMovie', name: 'new_movie')]
    public function create(Request $request): Response
    {
        $movie = new Movie();
        $form = $this->createForm(MovieAddType::class, $movie);
        $form->handleRequest($request);


        if($form->isSubmitted() && $form->isValid())
        {
            $newMovie = $form->getData();
            $imagePath = $form->get('Image')->getData();
            if($imagePath)
            {
                $newFileName = uniqid() . '.' . $imagePath->guessExtension();

                try {
                    $imagePath->move(
                        $this->getParameter('kernel.project_dir') . '/public/uploads', $newFileName
                    );
                } catch(FileException $e){
                    return new Response($e->getMessage());
                }
                $newMovie->setImage('/uploads/'. $newFileName);
            }
            $this->em->persist($newMovie);
            $this->em->flush();
            return $this->redirectToRoute('home');
        }


        return $this->render('movies/add.html.twig', [
            'form'=>$form->createView()
        ]);
    }
    #[Route('/home/{id}',  name: 'movie', methods: ['GET'])]
    public function show($id): Response
    {
        $movie = $this->movieRepository->find($id);
        return $this->render('movies/movie.html.twig',[
            'movie'=>$movie
        ]);
    }
}
