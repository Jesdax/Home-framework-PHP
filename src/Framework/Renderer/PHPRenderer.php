<?php
namespace Framework\Renderer;


class PHPRenderer implements RendererInterface
{
    const DEFAULT_NAMESPACE = '__MAIN';

    private $paths = [];

    /**
     * Variables globalement accessible pour toutes les vues
     * @var array
     */
    private $globals = [];


    public function __construct($defaultPath = null)
    {
        if (!is_null($defaultPath)) {
            $this->addPath($defaultPath);
        }
    }


    /**
     * Permet de rajouter un chemin pour charger les vues
     * @param $namespace
     * @param null $path
     */
    public function addPath($namespace, $path = null)
    {

        if (is_null($path)) {
            $this->paths[self::DEFAULT_NAMESPACE] = $namespace;
        } else {
            $this->paths[$namespace] = $path;
        }
    }


    /**
     * Permet de rendre une vue
     * Le chemin peut être précisé avec des namespaces rajoutés via addPath()
     * $this->render('@blog/view');
     * $this->render('view');
     * @param $view
     * @param array $params
     * @return false|string
     */
    public function render($view, array $params = [])
    {

        if ($this->hasNamespace($view)) {
            $path = $this->replaceNameplace($view) . '.php';
        } else {
            $path = $this->paths[self::DEFAULT_NAMESPACE] . DIRECTORY_SEPARATOR . $view . '.php';
        }

        ob_start();
        $renderer = $this;
        extract($this->globals);
        extract($params);
        require($path);
        return ob_get_clean();
    }


    /**
     * Permet de rajouter des variables global à toutes les vues
     * @param string $key
     * @param $value
     */
    public function addGlobal(string $key, $value): void
    {
        $this->globals[$key] = $value;
    }

    private function hasNamespace($view)
    {
        return $view[0] === '@';
    }

    private function getNamespace($view)
    {
        return substr($view, 1, strpos($view, '/') - 1);
    }

    private function replaceNameplace($view)
    {
        $namespace = $this->getNamespace($view);
        return str_replace('@' . $namespace, $this->paths[$namespace], $view);
    }
}
