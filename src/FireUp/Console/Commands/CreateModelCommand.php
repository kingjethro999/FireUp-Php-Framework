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
        if (in_array('--help', $args)) {
            $this->showHelp();
            return 0;
        }

        if (empty($args)) {
            $this->error('Please specify a model name');
            $this->showHelp();
            return 1;
        }

        $modelName = array_shift($args);
        $modelPath = $this->app->getBasePath() . '/app/Models/' . $modelName . '.php';

        if (file_exists($modelPath)) {
            $this->error("Model {$modelName} already exists!");
            return 1;
        }

        $content = $this->generateModelContent($modelName);
        file_put_contents($modelPath, $content);

        $this->info("Model {$modelName} created successfully!");

        return 0;
    }

    /**
     * Generate the model file content.
     *
     * @param  string  $modelName
     * @return string
     */
    protected function generateModelContent($modelName)
    {
        return <<<EOT
<?php

namespace App\Models;

use FireUp\Database\Model;

class {$modelName} extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected \$table = '{$this->getTableName($modelName)}';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected \$fillable = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected \$hidden = [];
}
EOT;
    }

    /**
     * Get the table name from command arguments.
     *
     * @param  string  $modelName
     * @return string
     */
    protected function getTableName($modelName)
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $modelName)) . 's';
    }

    /**
     * Get the command signature.
     *
     * @return string
     */
    public function getSignature()
    {
        return 'create:model <name>';
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
        echo "Usage: fireup create:model <name>\n\n";
        echo "Arguments:\n";
        echo "  name              The name of the model class\n\n";
        echo "Options:\n";
        echo "  --help           Show this help message\n\n";
        echo "Example:\n";
        echo "  fireup create:model User\n";
    }
} 