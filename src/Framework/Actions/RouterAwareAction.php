<?php
namespace Framework\Actions;


use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

/**
 * Add methods related to using the Router
 * Trait RouterAwareAction
 * @package Framework\Actions
 */
trait RouterAwareAction
{

    /**
     * Returns a redirect response
     * @param $path
     * @param array $params
     * @return ResponseInterface
     */
    public function redirect($path, array $params = []): ResponseInterface
    {
        $redirectUri = $this->router->generateUri($path, $params);
        return (new Response())
            ->withStatus(301)
            ->withHeader('location', $redirectUri);
    }
}
