<?php

namespace App\Service;

use GuzzleHttp\Client;
use App\Entity\Movie;

class ImdbService implements MoviesApiInterface
{
    
    private Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => $_ENV['IMDB_BASE_URL'],
            'headers' => [
                'X-RapidAPI-Key' => $_ENV['IMDB_API_KEY'],
                'X-RapidAPI-Host' => $_ENV['IMDB_API_HOST'],
            ],
        ]);
    }

    public function getMovies(string $name): ?array
    {
        $movies = [];
        
        $response = $this->client->request("GET", "/title/find?q={$name}");
        $content = json_decode($response->getBody()->getContents(), true);
        
        if(isset($content['results'])) {
            foreach($content['results'] as $result) {
                if(isset($result['titleType']) && $result['titleType'] == "movie") {
                    $movies[] = $result;
                }
            }
        }

        return sizeof($movies) ? $movies : null;
    }

    public function saveMovie(Movie &$movie, array &$data)
    {
        foreach($data as $k => &$v) {
            switch($k) {
                case 'id':
                    $movie->setImdbId($v);
                    $data['imdb_id'] = $v;
                    break;
                case 'title':
                    $movie->setTitle($v);
                    break;
                case 'image':
                    $movie->setImage($v['url']);
                    $data['image'] = $v['url'];
                    break;
                case 'year':
                    $movie->setReleaseDate($v);
                    $data['release_date'] = $v;
                    break;
                case 'runningTimeInMinutes':
                    $movie->setRuntime($v);
                    $data['runtime'] = $v;
                    break;
                case 'principals':

                    // No keywords from API, so use actors...
                    $keywords = [];
                    foreach($v as $actor) {
                        $keywords[] = $actor['name'];
                    }

                    $movie->setKeywords(json_encode($keywords));
                    $data['keywords'] = $keywords;
                    break;
            }
        }
    }
}