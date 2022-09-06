<?php

use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('bookshelf')->name('bookshelf.')->group(function () {
    Route::resource('books', BookController::class);
    // middleware('auth:api')->
    Route::resource('authors', AuthorController::class);
    // middleware('auth:api')->
    Route::resource('pub-companies', PublishingController::class);

    Route::resource('categories', CategoryController::class);
    // middleware('auth:api')->
    // Route::resource('users', UserController::class);

    Route::get('/unauthenticated', function () {
        return [
            'error' => 'Usuário não está logado!'
        ];
    })->name('login');

    Route::post('auth/login', [AuthController::class, 'login']); //LOGIN USER
    Route::middleware('auth:api')->get('auth/logout', [AuthController::class, 'logout']); //LOGOUT USER
    Route::middleware('auth:api')->get('auth/me', [AuthController::class, 'me']); //USER PROFILE

    Route::post('/users', [AuthController::class, 'create']); //CREATE USER
    Route::middleware('auth:api')->get('users', [AuthController::class, 'index']); // GET USER
    Route::middleware('auth:api')->get('users/{id}', [AuthController::class, 'show']); // GET ONE USER
    Route::middleware('auth:api')->put('users/{id}', [AuthController::class, 'update']); // GET ONE USER
    Route::middleware('auth:api')->delete('users/{id}', [AuthController::class, 'destroy']); // GET ONE USER

});
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';
