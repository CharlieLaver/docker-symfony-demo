<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\MovieRepository;
use App\Service\MoviesApiInterface;
use App\Entity\Movie;

class MoviesController extends AbstractController
{
    private MoviesApiInterface $apiService;
    private MovieRepository $movieRepository;

    public function __construct(ManagerRegistry $doctrine, MoviesApiInterface $apiService)
    {
        $this->movieRepository = $doctrine->getRepository(Movie::class);
        $this->apiService = $apiService;
    }

    #[Route('/', name: 'app_movies', methods:['GET'])]
    public function index(): Response
    {
        return $this->render('movies/index.html.twig', [
            'movies' => $this->movieRepository->fetchAll(),
            'search' => false,
        ]);
    }

    #[Route('/search', name: 'app_movies_search', methods:['POST'])]
    public function search(Request $request): Response
    {
        $title = $request->request->get('title');
        $existingMovie = $this->movieRepository->fetch($title);

        if($existingMovie) {
            $moviesData = $existingMovie;
        } else {
            $moviesData = $this->apiService->getMovies($title);

            if($moviesData) {
                foreach($moviesData as &$movieData) {
                    $movie = new Movie();
                    $this->apiService->saveMovie($movie, $movieData);
                    $this->movieRepository->save($movie, $movie->getImdbId(), true);    
                }
            }
        }
        return $this->render('movies/index.html.twig', [
            'movies' => $moviesData,
            'search' => true,
        ]);
    }

    #[Route('/get', name: 'app_movies_get', methods:['GET'])]
    public function get() : Response
    {
        $response = new Response();
        $response->setContent(json_encode($this->movieRepository->fetchAll()));
        $response->setStatusCode(Response::HTTP_OK);
        $response->headers->set('Content-Type', 'application/json');
        $response->send();
    }
}