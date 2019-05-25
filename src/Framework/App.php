<?php

namespace Framework;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class App
{

    /**
     * @param ServerRequestInterface $request
     * @return \GuzzleHttp\Psr7\MessageTrait|Response
     */
    public function run(ServerRequestInterface $request)
    {
        $uri = $request->getUri()->getPath();

        if (!empty($uri) && $uri[-1] === "/") { //If url is not empty && last caract. in url equal "/" so pblm
            return (new Response())
                ->withStatus(301)
                ->withHeader('Location', substr($uri, 0, -1));
        }
        if ($uri === '/blog') {
            return (new Response(200, [], '<h1>Bienvenue sur le blog</h1>'));
        }
        return $response = new Response(404, [], '<h1>Error 404</h1>');
    }
}
