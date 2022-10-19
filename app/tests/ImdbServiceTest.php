<?php

use PHPUnit\Framework\TestCase;
use App\Service\ImdbService;

final class ImdbServiceTest extends TestCase
{
    // TODO
    // private ImdbService $imdbService; 
    // public function __construct(ImdbService $imdbService)
    // {
    //     $this->imdbService = $imdbService;
    // }

    // // Test that IMDB API is working  & returning expected data...
    // public function testGetMovies()
    // {
    //     $movie = $this->imdbService->getMovies('Inception');
        
    //     $this->assertIsArray($movie);
    //     $this->assertIsArray($movie['image']);
    //     $this->assertIsArray($movie['principals']);

    //     $this->assertArrayHasKey('id', $movie);
    //     $this->assertArrayHasKey('title', $movie);
    //     $this->assertArrayHasKey('image', $movie);
    //     $this->assertArrayHasKey('url', $movie['image']);
    //     $this->assertArrayHasKey('year', $movie);
    //     $this->assertArrayHasKey('runningTimeInMinutes', $movie);
    //     $this->assertArrayHasKey('principals', $movie);
        
    //     $this->assertNotNull($movie['id']);
    //     $this->assertNotNull($movie['title']);
    //     $this->assertNotNull($movie['image']);
    //     $this->assertNotNull($movie['url']);
    //     $this->assertNotNull($movie['year']);
    //     $this->assertNotNull($movie['runningTimeInMinutes']);
    //     $this->assertNotNull($movie['principals']);
    // }
}