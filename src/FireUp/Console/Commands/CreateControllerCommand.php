<?php

namespace FireUp\Console\Commands;

use FireUp\Application;

class CreateControllerCommand extends Command
{
    /**
     * Handle the command.
     *
     * @param  array  $args
     * @return int
     */
    public function handle(array $args)
    {
        if (empty($args[0])) {
            $this->error('Please provide a controller name.');
            $this->showHelp();
            return 1;
        }

        $controllerName = $args[0];
        $namespace = $this->getNamespace($args);
        $resource = in_array('--resource', $args);
        $api = in_array('--api', $args);
        $model = $this->getModel($args);

        // Create the controller file
        $controllerPath = $this->app->getBasePath() . '/app/Controllers/' . $controllerName . '.php';
        $controllerDir = dirname($controllerPath);

        if (!file_exists($controllerDir)) {
            mkdir($controllerDir, 0755, true);
        }

        if (file_exists($controllerPath) && !in_array('--force', $args)) {
            $this->error("Controller {$controllerName} already exists. Use --force to overwrite.");
            return 1;
        }

        $content = $this->generateControllerContent(
            $controllerName,
            $namespace,
            $resource,
            $api,
            $model
        );

        file_put_contents($controllerPath, $content);
        $this->info("Controller {$controllerName} created successfully!");

        return 0;
    }

    /**
     * Get the namespace from command arguments.
     *
     * @param  array  $args
     * @return string
     */
    protected function getNamespace(array $args)
    {
        foreach ($args as $arg) {
            if (strpos($arg, '--namespace=') === 0) {
                return substr($arg, 12);
            }
        }

        return 'App\\Controllers';
    }

    /**
     * Get the associated model from command arguments.
     *
     * @param  array  $args
     * @return string|null
     */
    protected function getModel(array $args)
    {
        foreach ($args as $arg) {
            if (strpos($arg, '--model=') === 0) {
                return substr($arg, 8);
            }
        }

        return null;
    }

    /**
     * Generate the controller file content.
     *
     * @param  string  $controllerName
     * @param  string  $namespace
     * @param  bool  $resource
     * @param  bool  $api
     * @param  string|null  $model
     * @return string
     */
    protected function generateControllerContent(
        $controllerName,
        $namespace,
        $resource,
        $api,
        $model
    ) {
        $content = "<?php\n\n";
        $content .= "namespace {$namespace};\n\n";
        $content .= "use FireUp\\Http\\Controller;\n";
        $content .= "use FireUp\\Http\\Request;\n";
        $content .= "use FireUp\\Http\\Response;\n";

        if ($model) {
            $content .= "use App\\Models\\{$model};\n";
        }

        $content .= "\nclass {$controllerName} extends Controller\n";
        $content .= "{\n";

        if ($resource) {
            if ($api) {
                $content .= $this->generateApiResourceMethods($model);
            } else {
                $content .= $this->generateResourceMethods($model);
            }
        } else {
            $content .= "    /**\n";
            $content .= "     * Display a listing of the resource.\n";
            $content .= "     *\n";
            $content .= "     * @param  Request  \$request\n";
            $content .= "     * @return Response\n";
            $content .= "     */\n";
            $content .= "    public function index(Request \$request)\n";
            $content .= "    {\n";
            $content .= "        return response()->json(['message' => 'Welcome to {$controllerName}']);\n";
            $content .= "    }\n";
        }

        $content .= "}\n";

        return $content;
    }

    /**
     * Generate API resource controller methods.
     *
     * @param  string|null  $model
     * @return string
     */
    protected function generateApiResourceMethods($model)
    {
        $content = "    /**\n";
        $content .= "     * Display a listing of the resource.\n";
        $content .= "     *\n";
        $content .= "     * @param  Request  \$request\n";
        $content .= "     * @return Response\n";
        $content .= "     */\n";
        $content .= "    public function index(Request \$request)\n";
        $content .= "    {\n";
        if ($model) {
            $content .= "        \$items = {$model}::all();\n";
            $content .= "        return response()->json(\$items);\n";
        } else {
            $content .= "        return response()->json(['message' => 'List all items']);\n";
        }
        $content .= "    }\n\n";

        $content .= "    /**\n";
        $content .= "     * Store a newly created resource in storage.\n";
        $content .= "     *\n";
        $content .= "     * @param  Request  \$request\n";
        $content .= "     * @return Response\n";
        $content .= "     */\n";
        $content .= "    public function store(Request \$request)\n";
        $content .= "    {\n";
        if ($model) {
            $content .= "        \$item = {$model}::create(\$request->all());\n";
            $content .= "        return response()->json(\$item, 201);\n";
        } else {
            $content .= "        return response()->json(['message' => 'Item created'], 201);\n";
        }
        $content .= "    }\n\n";

        $content .= "    /**\n";
        $content .= "     * Display the specified resource.\n";
        $content .= "     *\n";
        $content .= "     * @param  int  \$id\n";
        $content .= "     * @return Response\n";
        $content .= "     */\n";
        $content .= "    public function show(\$id)\n";
        $content .= "    {\n";
        if ($model) {
            $content .= "        \$item = {$model}::findOrFail(\$id);\n";
            $content .= "        return response()->json(\$item);\n";
        } else {
            $content .= "        return response()->json(['message' => 'Show item ' . \$id]);\n";
        }
        $content .= "    }\n\n";

        $content .= "    /**\n";
        $content .= "     * Update the specified resource in storage.\n";
        $content .= "     *\n";
        $content .= "     * @param  Request  \$request\n";
        $content .= "     * @param  int  \$id\n";
        $content .= "     * @return Response\n";
        $content .= "     */\n";
        $content .= "    public function update(Request \$request, \$id)\n";
        $content .= "    {\n";
        if ($model) {
            $content .= "        \$item = {$model}::findOrFail(\$id);\n";
            $content .= "        \$item->update(\$request->all());\n";
            $content .= "        return response()->json(\$item);\n";
        } else {
            $content .= "        return response()->json(['message' => 'Update item ' . \$id]);\n";
        }
        $content .= "    }\n\n";

        $content .= "    /**\n";
        $content .= "     * Remove the specified resource from storage.\n";
        $content .= "     *\n";
        $content .= "     * @param  int  \$id\n";
        $content .= "     * @return Response\n";
        $content .= "     */\n";
        $content .= "    public function destroy(\$id)\n";
        $content .= "    {\n";
        if ($model) {
            $content .= "        \$item = {$model}::findOrFail(\$id);\n";
            $content .= "        \$item->delete();\n";
            $content .= "        return response()->json(null, 204);\n";
        } else {
            $content .= "        return response()->json(['message' => 'Delete item ' . \$id], 204);\n";
        }
        $content .= "    }\n";

        return $content;
    }

    /**
     * Generate resource controller methods.
     *
     * @param  string|null  $model
     * @return string
     */
    protected function generateResourceMethods($model)
    {
        $content = "    /**\n";
        $content .= "     * Display a listing of the resource.\n";
        $content .= "     *\n";
        $content .= "     * @param  Request  \$request\n";
        $content .= "     * @return Response\n";
        $content .= "     */\n";
        $content .= "    public function index(Request \$request)\n";
        $content .= "    {\n";
        if ($model) {
            $content .= "        \$items = {$model}::all();\n";
            $content .= "        return view('items.index', compact('items'));\n";
        } else {
            $content .= "        return view('items.index');\n";
        }
        $content .= "    }\n\n";

        $content .= "    /**\n";
        $content .= "     * Show the form for creating a new resource.\n";
        $content .= "     *\n";
        $content .= "     * @return Response\n";
        $content .= "     */\n";
        $content .= "    public function create()\n";
        $content .= "    {\n";
        $content .= "        return view('items.create');\n";
        $content .= "    }\n\n";

        $content .= "    /**\n";
        $content .= "     * Store a newly created resource in storage.\n";
        $content .= "     *\n";
        $content .= "     * @param  Request  \$request\n";
        $content .= "     * @return Response\n";
        $content .= "     */\n";
        $content .= "    public function store(Request \$request)\n";
        $content .= "    {\n";
        if ($model) {
            $content .= "        \$item = {$model}::create(\$request->all());\n";
            $content .= "        return redirect()->route('items.index')\n";
            $content .= "            ->with('success', 'Item created successfully.');\n";
        } else {
            $content .= "        return redirect()->route('items.index')\n";
            $content .= "            ->with('success', 'Item created successfully.');\n";
        }
        $content .= "    }\n\n";

        $content .= "    /**\n";
        $content .= "     * Display the specified resource.\n";
        $content .= "     *\n";
        $content .= "     * @param  int  \$id\n";
        $content .= "     * @return Response\n";
        $content .= "     */\n";
        $content .= "    public function show(\$id)\n";
        $content .= "    {\n";
        if ($model) {
            $content .= "        \$item = {$model}::findOrFail(\$id);\n";
            $content .= "        return view('items.show', compact('item'));\n";
        } else {
            $content .= "        return view('items.show');\n";
        }
        $content .= "    }\n\n";

        $content .= "    /**\n";
        $content .= "     * Show the form for editing the specified resource.\n";
        $content .= "     *\n";
        $content .= "     * @param  int  \$id\n";
        $content .= "     * @return Response\n";
        $content .= "     */\n";
        $content .= "    public function edit(\$id)\n";
        $content .= "    {\n";
        if ($model) {
            $content .= "        \$item = {$model}::findOrFail(\$id);\n";
            $content .= "        return view('items.edit', compact('item'));\n";
        } else {
            $content .= "        return view('items.edit');\n";
        }
        $content .= "    }\n\n";

        $content .= "    /**\n";
        $content .= "     * Update the specified resource in storage.\n";
        $content .= "     *\n";
        $content .= "     * @param  Request  \$request\n";
        $content .= "     * @param  int  \$id\n";
        $content .= "     * @return Response\n";
        $content .= "     */\n";
        $content .= "    public function update(Request \$request, \$id)\n";
        $content .= "    {\n";
        if ($model) {
            $content .= "        \$item = {$model}::findOrFail(\$id);\n";
            $content .= "        \$item->update(\$request->all());\n";
            $content .= "        return redirect()->route('items.index')\n";
            $content .= "            ->with('success', 'Item updated successfully.');\n";
        } else {
            $content .= "        return redirect()->route('items.index')\n";
            $content .= "            ->with('success', 'Item updated successfully.');\n";
        }
        $content .= "    }\n\n";

        $content .= "    /**\n";
        $content .= "     * Remove the specified resource from storage.\n";
        $content .= "     *\n";
        $content .= "     * @param  int  \$id\n";
        $content .= "     * @return Response\n";
        $content .= "     */\n";
        $content .= "    public function destroy(\$id)\n";
        $content .= "    {\n";
        if ($model) {
            $content .= "        \$item = {$model}::findOrFail(\$id);\n";
            $content .= "        \$item->delete();\n";
            $content .= "        return redirect()->route('items.index')\n";
            $content .= "            ->with('success', 'Item deleted successfully.');\n";
        } else {
            $content .= "        return redirect()->route('items.index')\n";
            $content .= "            ->with('success', 'Item deleted successfully.');\n";
        }
        $content .= "    }\n";

        return $content;
    }

    /**
     * Get the command signature.
     *
     * @return string
     */
    public function getSignature()
    {
        return 'create:controller';
    }

    /**
     * Get the command description.
     *
     * @return string
     */
    public function getDescription()
    {
        return 'Create a new controller class';
    }

    /**
     * Show the command help.
     *
     * @return void
     */
    protected function showHelp()
    {
        echo "Usage: fireup create:controller <name> [options]\n\n";
        echo "Options:\n";
        echo "  --namespace=<namespace>  The namespace for the controller (default: App\\Controllers)\n";
        echo "  --resource              Create a resource controller\n";
        echo "  --api                   Create an API controller\n";
        echo "  --model=<model>         The associated model class\n";
        echo "  --force                 Overwrite the controller if it exists\n";
        echo "  --help                  Show this help message\n\n";
        echo "Example:\n";
        echo "  fireup create:controller UserController\n";
        echo "  fireup create:controller ProductController --resource --model=Product\n";
        echo "  fireup create:controller ApiController --api --model=Item\n";
    }
} 