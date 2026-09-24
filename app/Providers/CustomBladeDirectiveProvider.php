<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
class CustomBladeDirectiveProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Blade::directive('statusBadge', function ($status) {
            return "<?php if ($status == 'active'): ?>
                <span class=\"badge bg-success\">Active</span>
            <?php else: ?>
                <span class=\"badge bg-danger\">Inactive</span>
            <?php endif; ?>";
        });


        // Toggle Active / Inactive Status one 
        
        Blade::directive('toggleStatusStatic', function ($expression) {
            $expression = trim($expression, '()');
            
            return "<?php 
                \$isChecked = ($expression === 'active') ? 'checked' : '';
                echo '<div class=\"form-check form-switch\">
                        <input class=\"form-check-input\" type=\"checkbox\" role=\"switch\" ' . \$isChecked . ' onchange=\"toggleStatus(this)\" data-status=\"' . e($expression) . '\" >
                    </div>';
            ?>";
        });

        // Toggle staus directie status two
        Blade::directive('toggleStatusStatic', function ($expression) {
            $expression = trim($expression, '()');
            
            return "<?php 
                \$isChecked = ($expression === 'active') ? 'checked' : '';
                echo '<div class=\"form-check form-switch\">
                        <input class=\"form-check-input\" type=\"checkbox\" role=\"switch\" ' . \$isChecked . ' onchange=\"toggleStatus(this)\" data-status=\"' . e($expression) . '\" >
                    </div>';
            ?>";
        });

        
          
    }
}


