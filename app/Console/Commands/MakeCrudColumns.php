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
        $modelName = Str::studly($this->argument('name'));
        $columnsArg = $this->argument('columns');
        // Parse columns e.g. "title:string,body:text" -> [ ['name' => 'title', 'type' => 'string'], ... ]
        $columns = $this->parseColumns($columnsArg);

        $this->info("Processing CRUD files or fields for {$modelName}...");

        if($this->option('all')){
            $this->generateInterface($modelName,$columns);
            $this->generateRepository($modelName,$columns);
            $this->generateRequest($modelName, $columns);
            $this->GenerateModel($modelName, $columns);
            $this->generateMigration($modelName, $columns);
            $this->generateSeeder($modelName, $columns);
            $this->generateController($modelName, $columns);
            $this->indexBlade($modelName, $columns);
            $this->createBlade($modelName, $columns);
            $this->updateBlade($modelName, $columns);
            // $this->generateRoutes($modelName, $columns);
        }
        if ($this->option('repository')) {
            // Generate Interface
            $this->generateInterface($modelName, $columns);

            // Generate Repository
            $this->generateRepository($modelName, $columns);

            $this->info("Successfully generated interface and reposritory files!");
        }
        if ($this->option('request')) {
            // Generate Request
            $this->generateRequest($modelName, $columns);
            $this->info("Successfully generated Validation Request!");
        }

        if ($this->option('model')) {
            // Generate Model File
            $this->GenerateModel($modelName, $columns);
            $this->info("Successfully generated Model!");
        }

        if ($this->option('migration')) {
            // Generate Migration File
             $this->generateMigration($modelName, $columns);
             $this->info("Successfully generated Migration File!");
        }
        if ($this->option('seeder')) {
             // Generate Seeder File
            $this->generateSeeder($modelName , $columns);
            $this->info("Successfully generated Seeder File!");
        }

        if ($this->option('controller')) {
            $this->generateController($modelName, $columns);
            $this->info("Successfully generated Controller!");
        }
        if ($this->option('index')) {
             // Generate Livewire Blade File
            $this->indexBlade($modelName, $columns);
            $this->info("Successfully generated Index Blade File!");
        }
        if ($this->option('create')) {
             // Generate Livewire Blade File
            $this->createBlade($modelName, $columns);
            $this->info("Successfully generated Create Blade File!");
        }
        if ($this->option('update')) {
             // Generate Livewire Blade File
            $this->updateBlade($modelName, $columns);
            $this->info("Successfully generated Update Blade File!");
        }

        if ($this->option('route')) {
            $this->generateRoutes($modelName, $columns);
        }

    }

    // done
    protected function generateInterface($modelName, $columns)
    {
        $interfacePath = app_path("Repositories/Interfaces/{$modelName}RepositoryInterface.php");

        if (!File::exists(dirname($interfacePath))) {
            File::makeDirectory(dirname($interfacePath), 0755, true);
        }

        $stub = File::get(__DIR__ . '/stubs/interface.stub');
        $stub = str_replace('{{ModelName}}', $modelName, $stub);

        File::put($interfacePath, $stub);
    }

    protected function generateRepository($modelName, $columns)
    {
        $repositoryPath = app_path("Repositories/Files/{$modelName}Repository.php");

        if (!File::exists(dirname($repositoryPath))) {
            File::makeDirectory(dirname($repositoryPath), 0755, true);
        }
        $stub = File::get(__DIR__ . '/stubs/repository.stub');
        $stub = str_replace('{{ModelName}}', $modelName, $stub);

        File::put($repositoryPath, $stub);
    }
    protected function generateController($modelName)
    {
        $controllerPath = app_path("Http/Controllers/admin/{$modelName}Controller.php");

        if (!File::exists(dirname($controllerPath))) {
            File::makeDirectory(dirname($controllerPath), 0755, true);
        }

        $stub = File::get(__DIR__ . '/stubs/controller.stub');
        $stub = str_replace(['{{ModelName}}','{{modelName}}'], [$modelName,Str::camel($modelName)], $stub);

        File::put($controllerPath, $stub);
    }
    protected function parseColumns($columnsArg)
    {
        $parsed = [];
        $pairs = explode(',', $columnsArg);

        foreach ($pairs as $pair) {
            $parts = explode(':', $pair);
            $colName = trim($parts[0]);
            $colType = isset($parts[1]) ? trim($parts[1]) : 'string';

            // Check if additional parts exist (e.g., status:enum:pending,active,inactive)
            $allowedValues = [];
            if (strtolower($colType) === 'enum' && isset($parts[2])) {
                $allowedValues = array_map('trim', explode('|', $parts[2])); // e.g. pending|active|inactive
            }

            $parsed[] = [
                'name' => $colName,
                'type' => $colType,
                'allowed' => $allowedValues,
            ];
        }

        return $parsed;
    }
    protected function generateModel($modelName, array $columns)
    {
        $modelPath = app_path("Models/{$modelName}.php");

        if (!File::exists(dirname($modelPath))) {
            File::makeDirectory(dirname($modelPath), 0755, true);
        }

        // 1. Format the columns into a comma-separated string of quoted names (e.g., 'title', 'body', 'views')
        $fillableColumns = collect($columns)
            ->map(fn($column) => "'" . $column['name'] . "'")
            ->implode(', ');

        // 2. Get the stub and replace placeholders
        $stub = File::get(__DIR__ . '/stubs/model.stub');
        $stub = str_replace('{{ModelName}}', $modelName, $stub);
        $stub = str_replace('{{FillableColumns}}', $fillableColumns, $stub);

        // 3. Save the file
        File::put($modelPath, $stub);
    }

    protected function generateMigration($modelName, array $columns)
    {
        $tableName = Str::snake(Str::plural($modelName));
        $timestamp = date('Y_m_d_His');
        $migrationFileName = "{$timestamp}_create_{$tableName}_table.php";
        $migrationPath = database_path("migrations/{$migrationFileName}");

        if (!File::exists(dirname($migrationPath))) {
            File::makeDirectory(dirname($migrationPath), 0755, true);
        }

        // Build schema definition strings for each column
        $schemaDefinitions = '';
        // foreach ($columns as $column) {
        //     $definition = $this->getMigrationFieldType($column['name'], $column['type']);
        //     $schemaDefinitions .= "            {$definition}\n";
        // }
        //
        foreach ($columns as $column) {
            $definition = $this->getMigrationFieldType($column);
            $schemaDefinitions .= "            {$definition}\n";
        }

        $stub = File::get(__DIR__ . '/stubs/migration.stub');
        $stub = str_replace('{{TableName}}', $tableName, $stub);
        $stub = str_replace('{{SchemaDefinitions}}', trim($schemaDefinitions), $stub);

        File::put($migrationPath, $stub);
    }

    protected function getMigrationFieldType($column)
    {
        $name = $column['name'];
        switch (strtolower($name)) {
            case 'email':
                return "\$table->string('{$name}')->unique();";
            case 'slug':
                return "\$table->string('{$name}')->unique();";
        }
        $type = strtolower($column['type']);
        $allowed = $column['allowed'] ?? [];

        if ($type === 'enum') {
            // Format options array to string format for php code output: ['draft', 'published', 'archived']
            $optionsArray = "['" . implode("', '", $allowed) . "']";
            return "\$table->enum('{$name}', {$optionsArray});";
        }

        return match ($type) {
            'integer' => "\$table->integer('{$name}');",
            'mediumInteger' => "\$table->mediumInteger('{$name}');",
            'bigInteger' => "\$table->bigInteger('{$name}');",
            'smallInteger' => "\$table->smallInteger('{$name}');",
            'tinyInteger' => "\$table->tinyInteger('{$name}');",
            'decimal' => "\$table->decimal('{$name}', 8, 2);",
            'float' => "\$table->float('{$name}');",
            'double' => "\$table->double('{$name}');",
            'boolean' => "\$table->boolean('{$name}');",
            'date' => "\$table->date('{$name}');",
            'datetime' => "\$table->dateTime('{$name}');",
            'timestamp' => "\$table->timestamp('{$name}');",
            'time' => "\$table->time('{$name}');",
            'year' => "\$table->year('{$name}');",
            'text' => "\$table->text('{$name}');",
            'mediumText' => "\$table->mediumText('{$name}');",
            'longText' => "\$table->longText('{$name}');",
            'json' => "\$table->json('{$name}');",
            'uuid' => "\$table->uuid('{$name}');",
            default => "\$table->string('{$name}');",
        };
    }
    protected function generateRequest($modelName, array $columns)
    {
        $requestPath = app_path("Http/Requests/{$modelName}Request.php");

        if (!File::exists(dirname($requestPath))) {
            File::makeDirectory(dirname($requestPath), 0755, true);
        }

        $rulesString = '';
        foreach ($columns as $column) {
            $name = $column['name'];
            $type = $column['type'];

            // Pass both name and type to get intelligent rules
            $rule = $this->getDefaultRule($column);

            $rulesString .= "            '{$name}' => '{$rule}',\n";
        }

        $stub = File::get(__DIR__ . '/stubs/request.stub');
        $stub = str_replace('{{ModelName}}', $modelName, $stub);
        $stub = str_replace('{{Rules}}', trim($rulesString), $stub);

        File::put($requestPath, $stub);
    }
    protected function getDefaultRule($column)
    {
        $name = $column['name'];
        $type = strtolower($column['type']);
        $allowed = $column['allowed'] ?? [];

        if ($type === 'enum' && !empty($allowed)) {
            $allowedString = implode(',', $allowed);
            return "required|string|in:{$allowedString}";
        }
       // Optional: Smart handling based on common field names regardless of type
        switch (strtolower($name)) {
            case 'email':
                return 'required|string|email|max:255|unique:users,email'; // adjust table name as needed
            case 'password':
                return 'required|string|min:8|confirmed';
            case 'phone':
            case 'mobile':
                return 'required|string|max:20';
            case 'slug':
                return 'required|string|max:255|alpha_dash';
            case 'website':
            case 'url':
                return 'required|url|max:255';
            case 'image':
            case 'photo':
            case 'avatar':
                return 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048';
            case 'file':
            case 'document':
                return 'nullable|file|mimes:pdf,doc,docx|max:5120';
        }

        // Standard mapping based on Laravel migration column types
        return match (strtolower($type)) {
            // Numbers
            'integer', 'biginteger', 'mediuminteger', 'smallinteger', 'tinyinteger' => 'required|integer',
            'unsignedinteger', 'unsignedbiginteger' => 'required|integer|min:0',
            'decimal', 'float', 'double' => 'required|numeric',

            // Strings & Text
            'char', 'string' => 'required|string|max:255',
            'text', 'mediumtext', 'longtext' => 'required|string',

            // Booleans
            'boolean' => 'required|boolean',

            // Dates & Times
            'date' => 'required|date',
            'datetime', 'timestamp' => 'required|date_format:Y-m-d H:i:s',
            'time' => 'required|date_format:H:i:s',
            'year' => 'required|digits:4|integer',

            // Special Data Types
            'json', 'jsonb' => 'required|json',
            'uuid' => 'required|uuid',
            'ipaddress' => 'required|ip',
            'macaddress' => 'required|mac_address',

            // Fallback default
            default => 'required|string|max:255',
        };
    }

    public function generateSeeder($modelName)
    {
        $seederName = "{$modelName}Seeder";
        $seederPath = database_path("seeders/{$seederName}.php");

        $stub = File::get(__DIR__.'/stubs/seeder.stub');
        $stub = str_replace('{{ModelName}}', $modelName, $stub);
        $stub = str_replace('{{modelName}}', strtolower($modelName), $stub);

        File::put($seederPath, $stub);

        return $seederPath;
    }

    // indexBlade
    public function indexBlade($modelName, array $columns)
    {
        $indexBladePath = resource_path("views/admin/{$modelName}s/index.blade.php");

        if (!File::exists(dirname($indexBladePath))) {
            File::makeDirectory(dirname($indexBladePath), 0755, true);
        }

        $lowerModelName = strtolower($modelName);

        // Grab only the first two columns safely
        $firstTwoColumns = array_slice($columns, 0, 2);

        $tableHeaders = '';
        $tableColumns = '';

        foreach ($firstTwoColumns as $column) {
            $colName = $column['name'];
            $studlyName = Str::studly($colName);

            // Generate Header (e.g., <th>Title</th>)
            $tableHeaders .= "<th>{$studlyName}</th>\n            ";

            // Generate Cell (e.g., <td>{{ $post->title }}</td>)
            $tableColumns .= "<td>{{ \${$lowerModelName}->{$colName} }}</td>\n            ";
        }

        $stub = File::get(__DIR__ . '/stubs/indexBlade.stub');
        $stub = str_replace('{{ModelName}}', $modelName, $stub);
        $stub = str_replace('{{modelName}}', $lowerModelName, $stub);
        $stub = str_replace('{{TableHeaders}}', trim($tableHeaders), $stub);
        $stub = str_replace('{{TableColumns}}', trim($tableColumns), $stub);

        File::put($indexBladePath, $stub);
    }

    // createBlade
    public function createBlade($modelName, array $columns)
    {
        $createBladePath = resource_path("views/admin/{$modelName}s/create.blade.php");

        if (!File::exists(dirname($createBladePath))) {
            File::makeDirectory(dirname($createBladePath), 0755, true);
        }

        $lowerModelName = strtolower($modelName);
        $formFields = '';

        foreach ($columns as $column) {
            $name = $column['name'];
            $type = strtolower($column['type']);
            $label = Str::headline($name); // turns 'first_name' into 'First Name'
            $allowed = $column['allowed'] ?? [];

            // Determine input control type based on column specifications
            if ($type === 'enum' && !empty($allowed)) {
                // Generate a Select Dropdown for Enum fields
                $options = '';
                foreach ($allowed as $option) {
                    $optionTitle = Str::headline($option);
                    $options .= "<option value=\"{$option}\" {{ old('{$name}') == '{$option}' ? 'selected' : '' }}>{$optionTitle}</option>\n";
                }

                $inputControl = "
                    <select name=\"{$name}\" id=\"{$name}-column\" class=\"form-control @error('{$name}') is-invalid @enderror\">
                        <option value=\"\">Select {$label}</option>
                        {$options}
                    </select>";
            } elseif (in_array($type, ['text', 'mediumtext', 'longtext'])) {
                // Generate a Textarea for large text fields
                $inputControl = "
                    <textarea name=\"{$name}\" id=\"{$name}-column\" class=\"form-control @error('{$name}') is-invalid @enderror\" placeholder=\"{$label}\">{{ old('{$name}') }}</textarea>";
            } else {
                // Default input type (text, number, email, etc.)
                $inputType = in_array($type, ['integer', 'biginteger', 'decimal', 'float', 'double']) ? 'number' : 'text';
                if ($name === 'email') {
                    $inputType = 'email';
                } elseif ($name === 'password') {
                    $inputType = 'password';
                }

                $inputControl = "
                    <input type=\"{$inputType}\" id=\"{$name}-column\" value=\"{{ old('{$name}') }}\" class=\"form-control @error('{$name}') is-invalid @enderror\" placeholder=\"{$label}\" name=\"{$name}\">";
            }

            // Assemble the grid wrapper structure (each field inside a Bootstrap col-md-6)
            $formFields .= "
                <div class=\"col-md-6 col-12\">
                    <div class=\"form-group\">
                        <label for=\"{$name}-column\">{$label}</label>
                        {$inputControl}
                        @error('{$name}')
                            <div class=\"invalid-feedback\">
                                {{ \$message }}
                            </div>
                        @enderror
                    </div>
                </div>\n";
        }

        $stub = File::get(__DIR__ . '/stubs/createBlade.stub');
        $stub = str_replace('{{ModelName}}', $modelName, $stub);
        $stub = str_replace('{{modelName}}', $lowerModelName, $stub);
        $stub = str_replace('{{FormFields}}', trim($formFields), $stub);

        File::put($createBladePath, $stub);
    }

    // updateBlade
    public function updateBlade($modelName, array $columns)
    {
        // Usually named edit.blade.php or update.blade.php depending on your preference
        $updateBladePath = resource_path("views/admin/{$modelName}s/edit.blade.php");

        if (!File::exists(dirname($updateBladePath))) {
            File::makeDirectory(dirname($updateBladePath), 0755, true);
        }

        $lowerModelName = strtolower($modelName);
        $formFields = '';

        foreach ($columns as $column) {
            $name = $column['name'];
            $type = strtolower($column['type']);
            $label = Str::headline($name);
            $allowed = $column['allowed'] ?? [];

            // Determine input control type based on column specifications with model value binding
            if ($type === 'enum' && !empty($allowed)) {
                $options = '';
                foreach ($allowed as $option) {
                    $optionTitle = Str::headline($option);
                    // Bind old input or existing model attribute value
                    $options .= "<option value=\"{$option}\" {{ (old('{$name}', \${$lowerModelName}->{$name}) == '{$option}') ? 'selected' : '' }}>{$optionTitle}</option>\n";
                }

                $inputControl = "
                    <select name=\"{$name}\" id=\"{$name}-column\" class=\"form-control @error('{$name}') is-invalid @enderror\">
                        <option value=\"\">Select {$label}</option>
                        {$options}
                    </select>";
            } elseif (in_array($type, ['text', 'mediumtext', 'longtext'])) {
                $inputControl = "
                    <textarea name=\"{$name}\" id=\"{$name}-column\" class=\"form-control @error('{$name}') is-invalid @enderror\" placeholder=\"{$label}\">{{ old('{$name}', \${$lowerModelName}->{$name}) }}</textarea>";
            } else {
                $inputType = in_array($type, ['integer', 'biginteger', 'decimal', 'float', 'double']) ? 'number' : 'text';
                if ($name === 'email') {
                    $inputType = 'email';
                } elseif ($name === 'password') {
                    $inputType = 'password';
                }

                $inputControl = "
                    <input type=\"{$inputType}\" id=\"{$name}-column\" value=\"{{ old('{$name}', \${$lowerModelName}->{$name}) }}\" class=\"form-control @error('{$name}') is-invalid @enderror\" placeholder=\"{$label}\" name=\"{$name}\">";
            }

            $formFields .= "
                <div class=\"col-md-6 col-12\">
                    <div class=\"form-group\">
                        <label for=\"{$name}-column\">{$label}</label>
                        {$inputControl}
                        @error('{$name}')
                            <div class=\"invalid-feedback\">
                                {{ \$message }}
                            </div>
                        @enderror
                    </div>
                </div>\n";
        }

        $stub = File::get(__DIR__ . '/stubs/updateBlade.stub');
        $stub = str_replace('{{ModelName}}', $modelName, $stub);
        $stub = str_replace('{{modelName}}', $lowerModelName, $stub);
        $stub = str_replace('{{FormFields}}', trim($formFields), $stub);

        File::put($updateBladePath, $stub);
    }
}
