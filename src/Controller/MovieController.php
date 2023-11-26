<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Entity\ReviewNRating;
use App\Form\MovieAddType;
use App\Form\ReviewFormType;
use App\Repository\MovieRepository;
use App\Repository\ReviewNRatingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{
    private $em;

    private $movieRepository;

    public function __construct(MovieRepository $movieRepository,ReviewNRatingRepository $reviewRepository, EntityManagerInterface $em)
    {
        $this->movieRepository = $movieRepository;
        $this->reviewRepository = $reviewRepository;
        $this->em = $em;
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

    #[Route('/home/{id}',  name: 'movie', methods: ['GET', 'POST'])]
    public function show($id, Request $request): Response
    {
        $movie = $this->movieRepository->find($id);


        //Review submission
        $review = new ReviewNRating();
        $form = $this->createForm(ReviewFormType::class, $review);
        $form->handleRequest($request);

        // getting user id for pivot table
        $user = $this->getUser();

        // form handling
        if($form->isSubmitted() && $form->isValid())
        {
            $newReview = $form->getData();
            $review->setMovie($movie);
            $review->setUser($user);
            $rating = $form->get('rating')->getData();
            $review->setRating($rating);
            $this->em->persist($newReview);
            $this->em->flush();
            return $this->redirectToRoute('movie', ['id' => $id]);
        }
        //Getting movie reviews

        $reviews = $this->reviewRepository->findBy(['movie' => $movie]);

        // Calculate average rating
        $sumRatings = 0;
        $reviewCount = count($reviews);
        foreach ($reviews as $review) {
            $sumRatings += $review->getRating();
        }
        $averageRating = $reviewCount > 0 ? $sumRatings / $reviewCount : 0;


        return $this->render('movies/movie.html.twig',[
            'movie'         =>$movie,
            'reviewForm'    =>$form->createView(),
            'reviews'       => $reviews,
            'averaggeRating'=>$averageRating,
        ]);
    }


}
