<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

/**
 * Class MutatorGenerator
 * Generates a mutator class in the transactor/mutations directory.
 * @package App\Console\Commands
 */
class MutatorGenerator extends Command
{

    private const PATH = 'app/Transactors/Mutations/';
    private const NAMESPACE = "App\Transactors\Mutations";
    private const CLASS_NAME_POSTFIX = 'Mutator';
    private const CLASS_CONTENT = <<<EOF
<?php
namespace %s;

class %s extends BaseMutator{
    public function __construct()
    {
        parent::__construct('%s', '%s');
    }
}
EOF;

    // todo write something to generate test cases.
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mutator:generate {model} {column}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates a mutator for the given model. The model name must be a fully qualified name.
    Path to model must be separated by two \'\\\'.
    Sample usage
    php artisan mutator:generate App\\\\UserMapper id
    NOTE there are 2 \\ in the above command.
    ';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $modelName = $this->argument('model');
        $column = $this->argument('column');
        $this->generateMutation($modelName, $column);

    }

    private function generateMutation(string $model, string $column){
        $className = explode('\\', $model)[1];
        $fileContents = sprintf(self::CLASS_CONTENT, self::NAMESPACE, $className.self::CLASS_NAME_POSTFIX, $model, $column);
        try {
            $file = fopen(self::PATH . $className . self::CLASS_NAME_POSTFIX . ".php", 'w');
            fwrite($file, $fileContents);
            fclose($file);
        }catch (\Exception $e){
            error_log('Couldnt create file');
            error_log($e->getMessage());
        }
    }
}
