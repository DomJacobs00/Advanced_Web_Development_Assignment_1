<?php

namespace App\Controller;

use App\Entity\Actor;
use App\Entity\Director;
use App\Entity\Movie;
use App\Form\ApiMovieFormType;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
//use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Request;


class APIController extends AbstractFOSRestController
{
    private MovieRepository $movieRepository;
    private EntityManagerInterface $em;
    private SerializerInterface $serializer;

    public function __construct(MovieRepository $movieRepository, EntityManagerInterface $em, SerializerInterface $serializer)
    {
        $this->movieRepository = $movieRepository;
        $this->em = $em;
        $this->serializer = $serializer;
    }
    #[Rest\Get('/api/v1/movies', name: 'movie_list')]
    public function getMovies(): Response
    {
        $movies = $this->movieRepository->findAll();

        if (!$movies) {
            // Directly returning a Response with 204 No Content
            return new Response(null, Response::HTTP_NO_CONTENT);
        }
        $json = $this->serializer->serialize($movies, 'json', ['groups' => 'movie_details']);

        return new Response($json, 200, ['Content-Type' => 'application/json']);
    }

    #[Rest\Get('/api/v1/movies/search/{id}', name: 'movie_by_id', requirements: ['id' => '\d+'])]
    public function getMovie($id)
    {
        $movies = $this->movieRepository->find($id);

        if (!$movies) {
            // Directly returning a Response with 204 No Content
            return new Response(null, Response::HTTP_NO_CONTENT);
        }
        $json = $this->serializer->serialize($movies, 'json', ['groups' => 'movie_details']);

        return new Response($json, 200, ['Content-Type' => 'application/json']);

    }
    #[Rest\Get('/api/v1/movies/search/{name}', name: 'movie_by_name')]
    public function findMovieByName($name):Response
    {
        $criteria = ['Title'=>$name];
        $movies = $this->movieRepository->findBy($criteria);

        if (!$movies) {
            // Directly returning a Response with 204 No Content
            return new Response(null, Response::HTTP_NO_CONTENT);
        }
        $json = $this->serializer->serialize($movies, 'json', ['groups' => 'movie_details']);

        return new Response($json, 200, ['Content-Type' => 'application/json']);

    }
    #[Rest\Post('/api/v1/movies', name: 'add_movie')]
    public function postMovie(Request $request)
    {
        $movie = new Movie();
        $data = json_decode($request->getContent(), true);

        $form = $this->createForm(ApiMovieFormType::class, $movie);
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid())
        {
            $newMovie = $form->getData();
            // Getting data such as directors and actors, seperating them and creating entities with it
            $director = $form->get('directors')->getData();
            $directorsArray = explode(',', $director);
            $directorsArray = array_map('trim', $directorsArray);
            foreach ($directorsArray as $directorName)
            {
                $director = $this->em->getRepository(Director::class)->findOneByName($directorName);
                if(!$director)
                {
                    $director = new Director();
                    $director->setName($directorName);
                    $movie->addDirector($director);
                    $this->em->persist($director);

                } else{
                    $movie->addDirector($director);
                }

            }
            $actors = $form->get('Actors')->getData();
            $actorsArray = explode(',',$actors);
            $actorsArray = array_map('trim', $actorsArray);

            foreach ($actorsArray as $actorName)
            {
                $actor = $this->em->getRepository(Actor::class)->findOneByName($actorName);
                if(!$actor)
                {
                    $actor = new Actor();
                    $actor->setName($actorName);
                    $movie->addActor($actor);
                    $this->em->persist($actor);

                } else{
                    $movie->addActor($actor);
                }

            }


            $this->em->persist($newMovie);
            $this->em->flush();
            $json = $this->serializer->serialize($newMovie, 'json');
            return new Response($json, Response::HTTP_CREATED, ['Content-Type' => 'application/json']);
        } else {
            // Collect form error messages
            $formErrors = [];
            foreach ($form->getErrors(true) as $error) {
                $formErrors[] = $error->getMessage();
            }

            // Optionally add form errors to the response
            if (!empty($formErrors)) {
                $errorMessage = "";
                $errorMessage .= ' Details: ' . implode(' ', $formErrors);
            }

            return new Response(json_encode(['error' => $errorMessage]), Response::HTTP_BAD_REQUEST, ['Content-Type' => 'application/json']);
        }

    }
    #[Rest\Put('/api/v1/movies/{id}', name: 'update_movie')]
    public function updateMovie()
    {

    }
    #[Rest\Delete('/api/v1/movies/{id}', name: 'delete_movie')]
    public function removeMovie()
    {

    }

    #[Rest\Get('/api/v1/movies/{movieId}/comments', name: 'get_comments_for_movie')]
    #[Rest\Post('/api/v1/movies/{movieId}/comments', name: 'add_comment_to_movie')]
    #[Rest\Put('/api/v1/movies/{movieId}/comments/{commentId}', name: 'update_comment_for_movie')]
    #[Rest\Delete('/api/v1/movies/{movieId}/comments/{commentId}', name: 'delete_comment_for_movie')]





















    #[Rest\Get('/api/v1/movie/{name}', name: 'movie_by_name_OMDb')]
    public function getMovieByNameOMDb($name): Response
    {
        $client = new Client();

        $apiKey = 'dde5d30e';

        try {
            $response = $client->request('GET', 'http://www.omdbapi.com/', [
                'query' => [
                    'apikey'=>$apiKey,
                    't'=>$name,
                ]
            ]);

            $statusCode = $response->getStatusCode();
            $content = $response->getBody()->getContents();

            if ($statusCode == 200) {
                $movieData = json_decode($content, true);
                return $this->json($movieData);
            }
            return $this->json(['error' => 'Failed to fetch movie details'], 404);
        } catch (\Exception $e)
        {
            return $this->json(['error' => 'An error occurred'], 500);
        }


    }



}
