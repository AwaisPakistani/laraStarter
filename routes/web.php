<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SaleController;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\admin\{UserController, RoleController, PermissionController};
Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // import
    Route::post('/sales/import', [SaleController::class,'import']);
    Route::get('/sales/export', [SaleController::class, 'export'])
    ->name('sales.export');

    /////////////////
    Route::get('sales/download/{filename}', function (string $filename) {
        $path = 'exports/' . $filename;

        if (!Storage::disk('public')->exists($path)) {
            return redirect()->back()->with('error', 'Export file is still processing or does not exist. Please try again in a few seconds.');
        }

        return Storage::disk('public')->download($path);
    })->name('sales.download');
    ////////////////
    /////////ADMIN///////////
    Route::resource('users', UserController::class);
    Route::post('users/{userId}/change-status', [UserController::class, 'toggleStatus'])->name('roles.toggleStatus');
    // Permissions
    // Roles
    Route::resource('roles', RoleController::class);
     // Active/ Inactive Status
    Route::post('roles/{roleId}/change-status', [RoleController::class, 'toggleStatus'])->name('roles.toggleStatus');
    // Permissions

    Route::resource('permissions', PermissionController::class);

    Route::post('permissions/{permissionId}/change-status', [PermissionController::class, 'toggleStatus'])->name('users.toggleStatus');
    //
    Route::get('dashboard-one', [ProfileController::class, 'dashboard_one'])->name('dashboard_one');
});

require __DIR__.'/auth.php';
