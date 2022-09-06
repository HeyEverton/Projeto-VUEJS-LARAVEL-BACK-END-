<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PublishingController;
// use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('bookshelf')->name('bookshelf.')->group(function() {
    Route::middleware('auth:api')->resource('books', BookController::class);
    Route::middleware('auth:api')->get('books/pesquisar/titulo/{title}', [BookController::class, 'pesquisaLivroPorTitulo']);
    Route::middleware('auth:api')->get('books/edit/{id}', [BookController::class, 'showEdit']);

 

    //AUTORES
    Route::middleware('auth:api')->resource('authors', AuthorController::class);
    Route::middleware('auth:api')->get('authors/pesquisar/nome/{name}', [AuthorController::class, 'pesquisarAutorPorNome']);
    Route::middleware('auth:api')->get('authors/pesquisar/sobrenome/{last_name}', [AuthorController::class, 'pesquisarAutorPorSobrenome']);

    //EDITORAS
    Route::middleware('auth:api')->resource('pub-companies', PublishingController::class);
    Route::middleware('auth:api')->get('pub-companies/pesquisar/nome/{name}', [PublishingController::class, 'pesquisarEditoraPorNome']);
    Route::middleware('auth:api')->get('pub-companies/pesquisar/endereco/{address}', [PublishingController::class, 'pesquisarEditoraPorEndereco']);
    Route::middleware('auth:api')->get('pub-companies/pesquisar/site/{website}', [PublishingController::class, 'pesquisarEditoraPorSite']);

    //CATEGORIAS
    Route::middleware('auth:api')->resource('categories', CategoryController::class);
    Route::middleware('auth:api')->get('categories/pesquisar/nome/{name}', [CategoryController::class, 'pesquisarCategoriaPorNome']); //


    // Route::resource('users', UserController::class);

    Route::get('/unauthenticated', function () {
        return [
            'error' => 'Usuário não está logado!'
        ];
    })->name('login');

    Route::post('auth/login', [AuthController::class, 'login']); //LOGIN USER
    Route::middleware('auth:api')->get('auth/logout', [AuthController::class, 'logout']); //LOGOUT USER
    Route::middleware('auth:api')->get('auth/me', [AuthController::class, 'me']); //USER PROFILE

    Route::middleware('auth:api')->post('/user/create', [AuthController::class, 'create']); //CREATE USER
    Route::middleware('auth:api')->get('users', [AuthController::class, 'index']); // GET ALL USERS
    Route::middleware('auth:api')->get('users/{id}', [AuthController::class, 'show']); // GET ONE USER/ GET ONE USER
    Route::middleware('auth:api')->put('users/{id}', [AuthController::class, 'update']); // GET ONE USER
    Route::middleware('auth:api')->delete('users/{id}', [AuthController::class, 'destroy']); // GET ONE USER
   

});
