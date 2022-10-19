<?php

namespace App\Service;
use App\Entity\Movie;

interface MoviesApiInterface
{
    public function getMovies(string $movie): ?array;
    public function saveMovie(Movie &$movie, array &$data);
}