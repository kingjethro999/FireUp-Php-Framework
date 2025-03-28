<?php

namespace FireUp\Console\Commands;

use FireUp\Application;

class CreateModelCommand extends Command
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
            $this->error('Please provide a model name.');
            $this->showHelp();
            return 1;
        }

        if (in_array('--help', $args)) {
            $this->showHelp();
            return 0;
        }

        $modelName = $args[0];
        $namespace = $this->getNamespace($args);
        $table = $this->getTableName($args);
        $fillable = $this->getFillable($args);
        $hidden = $this->getHidden($args);
        $timestamps = !in_array('--no-timestamps', $args);
        $softDeletes = in_array('--soft-deletes', $args);

        // Create the model file
        $modelPath = $this->app->getBasePath() . '/app/Models/' . $modelName . '.php';
        $modelDir = dirname($modelPath);

        if (!file_exists($modelDir)) {
            mkdir($modelDir, 0755, true);
        }

        if (file_exists($modelPath)) {
            if (!in_array('--force', $args)) {
                $this->error("Model {$modelName} already exists. Use --force to overwrite.");
                return 1;
            }
        }

        $content = $this->generateModelContent(
            $modelName,
            $namespace,
            $table,
            $fillable,
            $hidden,
            $timestamps,
            $softDeletes
        );

        file_put_contents($modelPath, $content);
        $this->info("Model {$modelName} created successfully!");

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

        return 'App\\Models';
    }

    /**
     * Get the table name from command arguments.
     *
     * @param  array  $args
     * @return string
     */
    protected function getTableName(array $args)
    {
        foreach ($args as $arg) {
            if (strpos($arg, '--table=') === 0) {
                return substr($arg, 8);
            }
        }

        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $args[0]));
    }

    /**
     * Get the fillable properties from command arguments.
     *
     * @param  array  $args
     * @return array
     */
    protected function getFillable(array $args)
    {
        foreach ($args as $arg) {
            if (strpos($arg, '--fillable=') === 0) {
                return explode(',', substr($arg, 11));
            }
        }

        return [];
    }

    /**
     * Get the hidden properties from command arguments.
     *
     * @param  array  $args
     * @return array
     */
    protected function getHidden(array $args)
    {
        foreach ($args as $arg) {
            if (strpos($arg, '--hidden=') === 0) {
                return explode(',', substr($arg, 9));
            }
        }

        return ['password'];
    }

    /**
     * Generate the model file content.
     *
     * @param  string  $modelName
     * @param  string  $namespace
     * @param  string  $table
     * @param  array  $fillable
     * @param  array  $hidden
     * @param  bool  $timestamps
     * @param  bool  $softDeletes
     * @return string
     */
    protected function generateModelContent(
        $modelName,
        $namespace,
        $table,
        $fillable,
        $hidden,
        $timestamps,
        $softDeletes
    ) {
        $content = "<?php\n\n";
        $content .= "namespace {$namespace};\n\n";
        $content .= "use FireUp\\Database\\Model;\n";
        
        if ($softDeletes) {
            $content .= "use FireUp\\Database\\SoftDeletes;\n";
        }
        
        $content .= "\nclass {$modelName} extends Model\n";
        $content .= "{\n";
        
        if ($softDeletes) {
            $content .= "    use SoftDeletes;\n\n";
        }
        
        $content .= "    /**\n";
        $content .= "     * The table associated with the model.\n";
        $content .= "     *\n";
        $content .= "     * @var string\n";
        $content .= "     */\n";
        $content .= "    protected \$table = '{$table}';\n\n";
        
        if (!empty($fillable)) {
            $content .= "    /**\n";
            $content .= "     * The attributes that are mass assignable.\n";
            $content .= "     *\n";
            $content .= "     * @var array\n";
            $content .= "     */\n";
            $content .= "    protected \$fillable = [\n";
            foreach ($fillable as $field) {
                $content .= "        '{$field}',\n";
            }
            $content .= "    ];\n\n";
        }
        
        if (!empty($hidden)) {
            $content .= "    /**\n";
            $content .= "     * The attributes that should be hidden for serialization.\n";
            $content .= "     *\n";
            $content .= "     * @var array\n";
            $content .= "     */\n";
            $content .= "    protected \$hidden = [\n";
            foreach ($hidden as $field) {
                $content .= "        '{$field}',\n";
            }
            $content .= "    ];\n\n";
        }
        
        if (!$timestamps) {
            $content .= "    /**\n";
            $content .= "     * Indicates if the model should be timestamped.\n";
            $content .= "     *\n";
            $content .= "     * @var bool\n";
            $content .= "     */\n";
            $content .= "    public \$timestamps = false;\n\n";
        }
        
        $content .= "}\n";
        
        return $content;
    }

    /**
     * Get the command signature.
     *
     * @return string
     */
    public function getSignature()
    {
        return 'create:model';
    }

    /**
     * Get the command description.
     *
     * @return string
     */
    public function getDescription()
    {
        return 'Create a new model class';
    }

    /**
     * Show the command help.
     *
     * @return void
     */
    protected function showHelp()
    {
        echo "Usage: fireup create:model <name> [options]\n\n";
        echo "Options:\n";
        echo "  --namespace=<namespace>  The namespace for the model (default: App\\Models)\n";
        echo "  --table=<table>         The table name (default: snake_case of model name)\n";
        echo "  --fillable=<fields>      Comma-separated list of fillable fields\n";
        echo "  --hidden=<fields>        Comma-separated list of hidden fields\n";
        echo "  --no-timestamps          Disable timestamps\n";
        echo "  --soft-deletes          Enable soft deletes\n";
        echo "  --force                 Overwrite the model if it exists\n";
        echo "  --help                  Show this help message\n\n";
        echo "Example:\n";
        echo "  fireup create:model User\n";
        echo "  fireup create:model Product --table=products --fillable=name,price,description\n";
        echo "  fireup create:model Post --namespace=Blog\\Models --soft-deletes\n";
    }
} 