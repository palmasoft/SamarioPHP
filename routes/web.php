<?php
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
//
///
/////

Route::get('/', function () {
  return view('welcome');
});

Route::get('/dashboard', function () {
  return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


/////

Route::middleware(['auth'])->group(function () {
  Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
  Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
  Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

  Route::middleware(['role:organizer|superadmin'])->get('/create-rifa', function () {
    return view('create-rifa');
  });

  Route::middleware(['role:client'])->get('/buy-number', function () {
    return view('buy-number');
  });

  Route::middleware(['permission:create-organizer'])->post('/create-organizer', function () {
    // Crear organizador
  });
});



require __DIR__ . '/auth.php';
