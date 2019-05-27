<?php

namespace Framework\Renderer;

interface RendererInterface
{
    /**
     * Permet de rajouter un chemin pour charger les vues
     * @param $namespace
     * @param null $path
     */
    public function addPath($namespace, $path = null);

    /**
     * Permet de rendre une vue
     * Le chemin peut être précisé avec des namespaces rajoutés via addPath()
     * $this->render('@blog/view');
     * $this->render('view');
     * @param $view
     * @param array $params
     * @return false|string
     */
    public function render($view, array $params = []);

    /**
     * Permet de rajouter des variables global à toutes les vues
     * @param string $key
     * @param $value
     */
    public function addGlobal(string $key, $value): void;
}
