<?php

namespace FireUp\Routing;

use FireUp\Application;
use FireUp\Http\Controllers\ApiController;
use Illuminate\Support\Str;

class ApiRouteGenerator
{
    /**
     * The application instance.
     *
     * @var Application
     */
    protected $app;

    /**
     * Create a new API route generator instance.
     *
     * @param  Application  $app
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Generate API routes for a model.
     *
     * @param  string  $model
     * @param  string  $controller
     * @return void
     */
    public function generateRoutes($model, $controller)
    {
        $router = $this->app->getRouter();
        $baseUri = strtolower(Str::classBasename($model));

        // GET /api/{resource}
        $router->get("/api/{$baseUri}", [$controller, 'index']);

        // POST /api/{resource}
        $router->post("/api/{$baseUri}", [$controller, 'store']);

        // GET /api/{resource}/{id}
        $router->get("/api/{$baseUri}/{id}", [$controller, 'show']);

        // PUT /api/{resource}/{id}
        $router->put("/api/{$baseUri}/{id}", [$controller, 'update']);

        // DELETE /api/{resource}/{id}
        $router->delete("/api/{$baseUri}/{id}", [$controller, 'destroy']);
    }

    /**
     * Generate a resource API controller.
     *
     * @param  string  $model
     * @return string
     */
    public function generateController($model)
    {
        $controllerName = Str::classBasename($model) . 'Controller';
        $namespace = 'App\\Controllers\\Api';
        $fullClassName = $namespace . '\\' . $controllerName;

        $content = "<?php\n\n";
        $content .= "namespace {$namespace};\n\n";
        $content .= "use FireUp\\Http\\Controllers\\ApiController;\n";
        $content .= "use {$model};\n\n";
        $content .= "class {$controllerName} extends ApiController\n";
        $content .= "{\n";
        $content .= "    protected \$model;\n\n";
        $content .= "    public function __construct(\$app)\n";
        $content .= "    {\n";
        $content .= "        parent::__construct(\$app);\n";
        $content .= "        \$this->model = new {$model}();\n";
        $content .= "    }\n\n";
        $content .= "    public function index()\n";
        $content .= "    {\n";
        $content .= "        return \$this->success(\$this->model->all());\n";
        $content .= "    }\n\n";
        $content .= "    public function store()\n";
        $content .= "    {\n";
        $content .= "        \$data = \$this->app->getRequest()->all();\n";
        $content .= "        \$item = \$this->model->create(\$data);\n";
        $content .= "        return \$this->success(\$item, 'Created successfully');\n";
        $content .= "    }\n\n";
        $content .= "    public function show(\$id)\n";
        $content .= "    {\n";
        $content .= "        \$item = \$this->model->find(\$id);\n";
        $content .= "        return \$this->success(\$item);\n";
        $content .= "    }\n\n";
        $content .= "    public function update(\$id)\n";
        $content .= "    {\n";
        $content .= "        \$data = \$this->app->getRequest()->all();\n";
        $content .= "        \$item = \$this->model->update(\$id, \$data);\n";
        $content .= "        return \$this->success(\$item, 'Updated successfully');\n";
        $content .= "    }\n\n";
        $content .= "    public function destroy(\$id)\n";
        $content .= "    {\n";
        $content .= "        \$this->model->delete(\$id);\n";
        $content .= "        return \$this->success(null, 'Deleted successfully');\n";
        $content .= "    }\n";
        $content .= "}\n";

        $controllerPath = $this->app->getBasePath() . '/app/Controllers/Api/' . $controllerName . '.php';
        $this->ensureDirectoryExists(dirname($controllerPath));
        file_put_contents($controllerPath, $content);

        return $fullClassName;
    }

    /**
     * Ensure the directory exists.
     *
     * @param  string  $path
     * @return void
     */
    protected function ensureDirectoryExists($path)
    {
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }
    }
} 