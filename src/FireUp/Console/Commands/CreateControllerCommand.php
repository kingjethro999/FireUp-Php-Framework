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
        if (in_array('--help', $args)) {
            $this->showHelp();
            return 0;
        }

        if (empty($args)) {
            $this->error('Please specify a controller name');
            $this->showHelp();
            return 1;
        }

        $controllerName = array_shift($args);
        $controllerPath = $this->app->getBasePath() . '/app/Controllers/' . $controllerName . '.php';

        if (file_exists($controllerPath)) {
            $this->error("Controller {$controllerName} already exists!");
            return 1;
        }

        $content = $this->generateControllerContent($controllerName);
        file_put_contents($controllerPath, $content);

        $this->info("Controller {$controllerName} created successfully!");
        return 0;
    }

    /**
     * Generate the controller file content.
     *
     * @param  string  $controllerName
     * @return string
     */
    protected function generateControllerContent($controllerName)
    {
        return <<<EOT
<?php

namespace App\Controllers;

use FireUp\Http\Controller;
use FireUp\Http\Request;

class {$controllerName} extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \FireUp\Http\Response
     */
    public function index()
    {
        return view('index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \FireUp\Http\Response
     */
    public function create()
    {
        return view('create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \FireUp\Http\Request  \$request
     * @return \FireUp\Http\Response
     */
    public function store(Request \$request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  \$id
     * @return \FireUp\Http\Response
     */
    public function show(\$id)
    {
        return view('show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  \$id
     * @return \FireUp\Http\Response
     */
    public function edit(\$id)
    {
        return view('edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \FireUp\Http\Request  \$request
     * @param  int  \$id
     * @return \FireUp\Http\Response
     */
    public function update(Request \$request, \$id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  \$id
     * @return \FireUp\Http\Response
     */
    public function destroy(\$id)
    {
        //
    }
}
EOT;
    }

    /**
     * Get the command signature.
     *
     * @return string
     */
    public function getSignature()
    {
        return 'create:controller <name>';
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
        echo "Usage: fireup create:controller <name>\n\n";
        echo "Arguments:\n";
        echo "  name              The name of the controller class\n\n";
        echo "Options:\n";
        echo "  --help           Show this help message\n\n";
        echo "Example:\n";
        echo "  fireup create:controller UserController\n";
    }
} 