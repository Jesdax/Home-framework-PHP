<?php
namespace Framework\Renderer;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class TwigRenderer implements RendererInterface
{

    private $twig;

    private $loader;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function addPath($namespace, $path = null)
    {
        $this->twig->getLoader()->addPath($path, $namespace);
    }

    public function render($view, array $params = [])
    {
        return $this->twig->render($view . '.twig', $params);
    }

    public function addGlobal(string $key, $value): void
    {
        $this->twig->addGlobal($key, $value);
    }
}
