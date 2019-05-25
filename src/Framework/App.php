<?php

namespace Framework;


use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class App {


    public function run(ServerRequestInterface $request): ResponseInterface {

        $uri = $request->getUri()->getPath();
        if (!empty($uri) && $uri[-1] === "/") { //If url is not empty && last caract. in url equal "/" so pblm
            return (new Response())
                ->withStatus(301)
                ->withHeader('Location', substr($uri, 0, -1));
        }
        $response = new Response();
        $response->getBody()->write('Hello World');
        return $response;
    }


}



