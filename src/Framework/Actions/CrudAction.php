<?php


namespace App\Framework\Actions;



use Framework\Actions\RouterAwareAction;
use Framework\Database\Table;
use Framework\Renderer\RendererInterface;
use Framework\Router;
use Framework\Session\FlashService;
use Framework\Validator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;

class CrudAction
{
    /**
     * @var RendererInterface
     */
    private $renderer;


    /**
     * @var Table
     */
    private $table;


    /**
     * @var Router
     */
    private $router;


    /**
     * @var FlashService
     */
    private $flashService;

    /**
     * @var string
     */
    protected $viewPath;

    /**
     * @var string
     */
    protected $routePrefix;


    protected $msg = [
        'create' => "L'élément a bien été crée",
        'edit' => "L'élément a bien été modifié"
    ];

    use RouterAwareAction;


    public function __construct(
        RendererInterface $renderer,
        Router $router,
        Table $table,
        FlashService $flash
    ) {
        $this->renderer = $renderer;
        $this->router = $router;
        $this->table = $table;
        $this->flashService = $flash;
    }


    /**
     * @param Request $request
     * @return false|ResponseInterface|string
     * @throws \Exception
     */
    public function __invoke(Request $request)
    {
        $this->renderer->addGlobal('viewPath', $this->viewPath);
        $this->renderer->addGlobal('routePrefix', $this->routePrefix);

        if ($request->getMethod() === 'DELETE') {
            return $this->delete($request);
        }
        if (substr((string)$request->getUri(), -11) === 'new-article') {
            return $this->create($request);
        }
        if ($request->getAttribute('id')) {
            return $this->edit($request);
        }
        return $this->index($request);
    }


    /**
     * Show list elements
     * @param Request $request
     * @return string
     */
    public function index(Request $request): string
    {
        $params = $request->getQueryParams();
        $items = $this->table->findPaginated(12, $params['p'] ?? 1);
        return $this->renderer->render($this->viewPath . '/index', compact('items'));
    }

    /**
     * Edit a element
     * @param Request $request
     * @return false|ResponseInterface|string
     */
    public function edit(Request $request)
    {
        $item = $this->table->find($request->getAttribute('id'));

        if ($request->getMethod() === 'POST') {
            $params = $this->getParams($request);

            $validator = $this->getValidator($request);
            if ($validator->isValid()) {
                //var_dump($params);
                $this->table->update($item->id, $params);
                $this->flashService->success($this->msg['edit']);
                return $this->redirect($this->routePrefix . '.index');
            }
            $errors = $validator->getErrors();
            var_dump($errors);
            $params['id'] = $item->id;
            $item = $params;
        }

        $params = $this->formParams(compact('item', 'errors'));
        return $this->renderer->render($this->viewPath . '/edit', $params);
    }


    /**
     * Show element
     *
     * @param Request $request
     * @return ResponseInterface|string
     */
    public function show(Request $request)
    {
        $slug = $request->getAttribute('slug');

        $post = $this->table->find($request->getAttribute('id'));
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

    /**
     * Create a new element
     * @param Request $request
     * @return false|ResponseInterface|string
     * @throws \Exception
     */
    public function create(Request $request)
    {
        $item = $this->getNewEntity();
        if ($request->getMethod() === 'POST') {
            $params = $this->getParams($request);
            //var_dump($params);

            $validator = $this->getValidator($request);
            if ($validator->isValid()) {
                $this->table->insert($params);
                $this->flashService->success($this->msg['create']);
                return $this->redirect($this->routePrefix . '.index');
            }
            $item = $params;
            $errors = $validator->getErrors();
        }
        $params = $this->formParams(compact('item', 'errors'));
        return $this->renderer->render($this->viewPath . '/create', $params);
    }

    /**
     * @param Request $request
     * @return ResponseInterface
     */
    public function delete(Request $request)
    {
        $this->table->delete($request->getAttribute('id'));
        return $this->redirect($this->routePrefix . '.index');
    }

    /**
     * @param Request $request
     * @return array
     */
    protected function getParams(Request $request): array
    {
        return array_filter($request->getParsedBody(), function ($key) {
            return in_array($key, []);
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * @param Request $request
     * @return Validator
     */
    protected function getValidator(Request $request)
    {
        return new Validator($request->getParsedBody());
    }

    /**
     * @return array
     */
    protected function getNewEntity()
    {
        return [];
    }

    /**
     * Process the parameters to send to the view
     * @param $params
     * @return array
     */
    protected function formParams(array $params): array
    {
        return $params;
    }
}
