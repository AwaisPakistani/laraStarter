<?php

namespace App\Providers;

use Illuminate\Support\{ServiceProvider, Str};
use Illuminate\Pagination\Paginator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;
class AppServiceProvider extends ServiceProvider
{
   /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->autoBindRepositories();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
    }

     /**
    * Automatically bind repository interfaces with their implementations
    */
    protected function autoBindRepositories(): void
    {
        $interfacePath = app_path('Repositories/Interfaces');
        $repositoryPath = app_path('Repositories/Files');

        // Get all interface files
        $interfaces = $this->getPhpFilesInPath($interfacePath);

        foreach ($interfaces as $interface) {
            $interfaceName = $this->getClassNameFromFile($interface);
            $interfaceFullName = "App\\Repositories\\Interfaces\\{$interfaceName}";

            // Skip if not an interface
            if (!interface_exists($interfaceFullName)) {
                continue;
            }

            // Find matching repository
            $repositoryName = str_replace('Interface', '', $interfaceName);
            $repositoryFile = $this->findRepositoryFile($repositoryPath, $repositoryName);

            if ($repositoryFile) {
                $repositoryFullName = "App\\Repositories\\Files\\{$repositoryName}";
                $this->app->bind($interfaceFullName, $repositoryFullName);
            }
        }
    }

    /**
     * Get all PHP files in a given path
    */
    protected function getPhpFilesInPath(string $path): array
    {
        if (!file_exists($path)) {
            return [];
        }

        $directory = new RecursiveDirectoryIterator($path);
        $iterator = new RecursiveIteratorIterator($directory);
        $regex = new RegexIterator($iterator, '/^.+\.php$/i', RegexIterator::GET_MATCH);

        $files = [];
        foreach ($regex as $file) {
            $files[] = $file[0];
        }

        return $files;
    }
      /**
     * Get class name from file path
     */
    protected function getClassNameFromFile(string $filePath): string
    {
        $contents = file_get_contents($filePath);
        $tokens = token_get_all($contents);
        $className = '';

        for ($i = 0; $i < count($tokens); $i++) {
            if ($tokens[$i][0] === T_INTERFACE || $tokens[$i][0] === T_CLASS) {
                for ($j = $i + 1; $j < count($tokens); $j++) {
                    if ($tokens[$j] === '{' || $tokens[$j][0] === T_IMPLEMENTS) {
                        return $className;
                    }
                    if ($tokens[$j][0] === T_STRING) {
                        $className = $tokens[$j][1];
                    }
                }
            }
        }

        return '';
    }

    /**
     * Find repository file by name
     */
    protected function findRepositoryFile(string $repositoryPath, string $repositoryName): ?string
    {
        $files = $this->getPhpFilesInPath($repositoryPath);

        foreach ($files as $file) {
            $className = $this->getClassNameFromFile($file);
            if ($className === $repositoryName) {
                return $file;
            }
        }

        return null;
    }
}
