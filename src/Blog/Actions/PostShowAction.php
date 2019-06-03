<?php
namespace App\Blog\Actions;



use App\Blog\Table\PostTable;
use Framework\Actions\RouterAwareAction;
use Framework\Renderer\RendererInterface;
use Framework\Router;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;

class PostShowAction
{

    /**
     * @var RendererInterface
     */
    private $renderer;


    private $postTable;


    private $router;


    use RouterAwareAction;


    public function __construct(
        RendererInterface $renderer,
        Router $router,
        PostTable $postTable
    ) {
        $this->renderer = $renderer;
        $this->router = $router;
        $this->postTable = $postTable;
    }

    /**
     * Show article
     *
     * @param Request $request
     * @return ResponseInterface|string
     */
    public function __invoke(Request $request)
    {
        $slug = $request->getAttribute('slug');

        $post = $this->postTable->find($request->getAttribute('id'));
        if ($post->slug !== $slug) {
            return $this->redirect('blog.show', [
                'slug' => $post->slug,
                'id' => $post->id
            ]);
        }
        //var_dump($post);
        return $this->renderer->render('@blog/show', [
            'post' => $post
        ]);
    }
}
