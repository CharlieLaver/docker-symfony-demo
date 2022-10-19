<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Entity\Movie;

class ImdbService implements MoviesApiInterface
{
    private HttpClientInterface $client;
    private $key;
    private $host;
    private $baseUrl;

    public function __construct(HttpClientInterface $client, $key, $host, $baseUrl)
    {
        $this->client = $client;
        $this->key = $key;
        $this->host = $host;
        $this->baseUrl = $baseUrl;
    }

    public function getMovies(string $name): ?array
    {
        $movies = [];

        $response = $this->client->request('GET', "{$this->baseUrl}/title/find?q={$name}", [
            'headers' => [
                'X-RapidAPI-Key' => $this->key,
                'X-RapidAPI-Host'=> $this->host,
            ],
        ]);

        $content = $response->getContent();
        $content = $response->toArray();

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