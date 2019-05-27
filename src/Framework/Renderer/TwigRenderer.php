<?php
namespace Framework\Renderer;

class TwigRenderer implements RendererInterface
{

    private $twig;

    private $loader;

    public function __construct(string $path)
    {
        $this->loader = new \Twig\Loader\FilesystemLoader($path);
        $this->twig = new \Twig\Environment($this->loader, [

        ]);
    }

    public function addPath($namespace, $path = null)
    {
        $this->loader->addPath($path, $namespace);
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
