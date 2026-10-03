<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeCrudColumns extends Command
{
    protected $signature = 'make:crud-files
        {name : The name of the model/entity (e.g., Post)}
        {columns : Columns in format name:type (e.g., title:string,body:text,views:integer)}
        {--repository : Generate Repository Files}
        {--request : Generate a Request}
        {--migration : Generate a migration}
        {--model : Generate a Model}
        {--seeder : Generate a seeder}
        {--controller : Generate a controller}
        {--view : Generate a view}
        {--index : Generate a Index view}
        {--create : Generate a Creaet view}
        {--update : Generate a Update view}
        {--route : Generate a Route}
         {--all : Generate all files}';

    protected $description = 'Add columns to an existing Model ($fillable), create/update a Migration, and add validation to a Form Request';

    public function handle()
    {
        $name = Str::studly($this->argument('name'));
        $columnsArg = $this->argument('columns');
        // Parse columns e.g. "title:string,body:text" -> [ ['name' => 'title', 'type' => 'string'], ... ]
        $columns = $this->parseColumns($columnsArg);

        $this->info("Processing CRUD files or fields for {$name}...");

        if($this->option('all')){
            $this->generateInterface($modelName);
            $this->generateRepository($modelName);
            $this->generateRequest($name, $columns);
            $this->GenerateModel($name, $columns);
            $this->updateOrCreateMigration($name, $columns);
            $this->generateSeeder($modelName);
            $this->generateController($modelName);
            $this->indexBlade($modelName);
            $this->createBlade($modelName);
            $this->updateBlade($modelName);
            $this->generateRoutes($modelName);
        }
        if ($this->option('repository')) {
            // Generate Interface
            $this->generateInterface($modelName);

            // Generate Repository
            $this->generateRepository($modelName);

            $this->info("Successfully generated interface and reposritory files!");
        }
        if ($this->option('request')) {
            // Generate Request
            $this->generateRequest($name, $columns);
            $this->info("Successfully generated Validation Request!");
        }

        if ($this->option('model')) {
            // Generate Model File
            $this->GenerateModel($name, $columns);
            $this->info("Successfully generated Model!");
        }

        if ($this->option('migration')) {
            // Generate Migration File
             $this->updateOrCreateMigration($name, $columns);
             $this->info("Successfully generated Migration File!");
        }
        if ($this->option('seeder')) {
             // Generate Seeder File
            $this->generateSeeder($modelName);
            $this->info("Successfully generated Seeder File!");
        }

        if ($this->option('controller')) {
            $this->generateController($modelName);
            $this->info("Successfully generated Controller!");
        }
        if ($this->option('index')) {
             // Generate Livewire Blade File
            $this->indexBlade($modelName);
            $this->info("Successfully generated Index Blade File!");
        }
        if ($this->option('create')) {
             // Generate Livewire Blade File
            $this->createBlade($modelName);
            $this->info("Successfully generated Create Blade File!");
        }
        if ($this->option('update')) {
             // Generate Livewire Blade File
            $this->updateBlade($modelName);
            $this->info("Successfully generated Update Blade File!");
        }

        if ($this->option('route')) {
            $this->generateRoutes($modelName);
        }


    }

    protected function generateInterface($modelName)
    {
        $interfacePath = app_path("Repositories/Interfaces/{$modelName}RepositoryInterface.php");

        if (!File::exists(dirname($interfacePath))) {
            File::makeDirectory(dirname($interfacePath), 0755, true);
        }

        $stub = File::get(__DIR__ . '/stubs/interface.stub');
        $stub = str_replace('{{ModelName}}', $modelName, $stub);

        File::put($interfacePath, $stub);
    }

    protected function generateRepository($modelName)
    {
        $repositoryPath = app_path("Repositories/Files/{$modelName}Repository.php");

        if (!File::exists(dirname($repositoryPath))) {
            File::makeDirectory(dirname($repositoryPath), 0755, true);
        }

        $stub = File::get(__DIR__ . '/stubs/repository.stub');
        $stub = str_replace('{{ModelName}}', $modelName, $stub);

        File::put($repositoryPath, $stub);
    }
    protected function parseColumns($columnsArg)
    {
        $parsed = [];
        $pairs = explode(',', $columnsArg);

        foreach ($pairs as $pair) {
            [$colName, $colType] = explode(':', $pair) + [1 => 'string'];
            $parsed[] = [
                'name' => trim($colName),
                'type' => trim($colType),
            ];
        }

        return $parsed;
    }

    protected function GenerateModel($name, $columns)
    {
        $modelPath = app_path("Models/{$name}.php");

        if (!File::exists($modelPath)) {
            $this->warn("Model {$name} does not exist. Skipping fillable update.");
            return;
        }

        $content = File::get($modelPath);
        $columnNames = array_column($columns, 'name');

        // Check if $fillable exists, otherwise inject it
        if (str_contains($content, 'protected $fillable')) {
            // Simple regex to append to $fillable array
            $newFields = "'" . implode("',\n        '", $columnNames) . "',\n    ";
            // Insert before the closing bracket of fillable
            $content = preg_replace(
                '/(protected\s+\$fillable\s*=\s*\[)(.*?)(\];)/s',
                '$1$2' . $newFields . '$3',
                $content
            );
        } else {
            // Inject $fillable right after class opening
            $fieldsString = "'" . implode("', '", $columnNames) . "'";
            $replacement = "class {$name} extends Model\n{\n    protected \$fillable = [{$fieldsString}];\n";
            $content = preg_replace('/class\s+' . $name . '\s+extends\s+Model\s*\{/', $replacement, $content);
        }

        File::put($modelPath, $content);
        $this->line("<info>Updated Model:</info> {$name}.php");
    }

    protected function updateOrCreateMigration($name, $columns)
    {
        $tableName = Str::snake(Str::plural($name));
        $migrationName = "add_columns_to_{$tableName}_table";

        // Generate migration using Artisan
        $this->call('make:migration', [
            'name' => $migrationName,
            '--table' => $tableName,
        ]);

        // Find the newly created migration file
        $migrationFiles = File::glob(database_path("migrations/*_{$migrationName}.php"));
        if (empty($migrationFiles)) return;

        $migrationPath = end($migrationFiles);
        $content = File::get($migrationPath);

        $schemaLines = "";
        foreach ($columns as $col) {
            $schemaLines .= "            \$table->{$col['type']}('{$col['name']}');\n";
        }

        // Insert schema lines inside the up() method schema->table closure
        $content = preg_replace(
            '/(Schema::table\(\''.$tableName.'\', function \(Blueprint \$table\) \{)/',
            "$1\n" . $schemaLines,
            $content
        );

        File::put($migrationPath, $content);
        $this->line("<info>Created/Updated Migration:</info> " . basename($migrationPath));
    }

    protected function generateRequest($name, $columns)
    {
        $requestName = "{$name}Request";
        $requestPath = app_path("Http/Requests/{$requestName}.php");

        if (!File::exists($requestPath)) {
            // Create form request if it doesn't exist
            $this->call('make:request', ['name' => $requestName]);
        }

        if (!File::exists($requestPath)) return;

        $content = File::get($requestPath);

        $rulesLines = "";
        foreach ($columns as $col) {
            $rule = $this->getDefaultRule($col['type']);
            $rulesLines .= "            '{$col['name']}' => '{$rule}',\n";
        }

        // Inject rules into rules() method return array
        $content = preg_replace(
            '/(public function rules\(\): array\s*\{[^}]*return\s*\[)/s',
            "$1\n" . $rulesLines,
            $content
        );

        File::put($requestPath, $content);
        $this->line("<info>Updated Form Request:</info> {$requestName}.php");
    }

    protected function getDefaultRule($type)
    {
        return match ($type) {
            'integer', 'bigInteger' => 'required|integer',
            'text', 'mediumText', 'longText' => 'required|string|max:255',
            'boolean' => 'required|boolean',
            'decimal', 'float', 'double' => 'required|numeric',
            'date', 'dateTime', 'timestamp' => 'required|date',
            default => 'required|string|max:255',
        };
    }
}
