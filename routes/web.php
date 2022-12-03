<?php

use Illuminate\Support\Facades\Route;

use App\Models\User;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\FavouriteController;
use App\Http\Controllers\TrashController;
use App\Http\Controllers\PairController;
use App\Http\Controllers\ShareController;

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

// Route::get('/', function () {
//     return view('home');
// })->name('home');

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // Route::get('/home', function(){
    //     return view('home');
    // })->name('home');
    
    Route::get('/explore', function(){
        return view('explore');
    })->name('explore');

    Route::get('/', [ImageController::class, 'index'])->name('home');
    Route::post('/image', [ImageController::class, 'store'])->name('image.store');
    Route::put('/image/{id}', [ImageController::class, 'update']);
    Route::post('/image/{id}', [ImageController::class, 'addToTrash']);
    Route::get('/arrangeImages/{arrangeBy}/{order}', [ImageController::class, 'arrangeImages']);

    Route::get('/archive', [ArchiveController::class, 'index'])->name('archive');
    Route::post('/archive/{id}', [ArchiveController::class, 'addToArchive']);
    Route::post('/unarchive/{id}', [ArchiveController::class, 'removeFromArchive']);
    Route::get('/arrangeArchivedImages/{arrangeBy}/{order}', [ArchiveController::class, 'arrangeArchivedImages']);

    Route::get('/favourite', [FavouriteController::class, 'index'])->name('favourite');
    Route::post('/favourite/{id}', [FavouriteController::class, 'addToFavourite']);
    Route::post('/unfavourite/{id}', [FavouriteController::class, 'removeFromFavourite']);
    Route::get('/arrangeFavouriteImages/{arrangeBy}/{order}', [FavouriteController::class, 'arrangeFavouriteImages']);

    Route::get('/trash', [TrashController::class, 'index'])->name('trash');
    Route::post('/restore/{id}', [TrashController::class, 'restore']);
    Route::delete('/delete/{id}', [TrashController::class, 'delete']);
    Route::get('/arrangeTrashImages/{arrangeBy}/{order}', [TrashController::class, 'arrangeTrashImages']);
    Route::post('/clear', [TrashController::class, 'clear']);

    Route::get('/sharing', [PairController::class, 'index'])->name('sharing');
    Route::get('/getUsers', [PairController::class, 'getUsers']);
    Route::get('/getFriends', [PairController::class, 'getFriends']);
    Route::get('/getRequests', [PairController::class, 'getRequests']);
    Route::get('/getRequestsSent', [PairController::class, 'getRequestsSent']);
    Route::get('/getEmail/{email}/{mode}', [PairController::class, 'getEmail']);
    Route::post('/sendRequest/{id}', [PairController::class, 'sendRequest']);
    Route::post('/deleteRequest/{id}', [PairController::class, 'deleteRequest']);
    Route::post('/acceptRequest/{id}', [PairController::class, 'acceptRequest']);
    Route::post('/rejectRequest/{id}', [PairController::class, 'rejectRequest']);
    Route::post('/removeFriend/{id}', [PairController::class, 'removeFriend']);

    Route::post('/share/{userId}/{imageId}', [ShareController::class, 'share']);
    Route::post('/unshare/{userId}/{imageId}', [ShareController::class, 'unshare']);
    Route::get('/arrangeSharedImages/{arrangeBy}/{order}', [ShareController::class, 'arrangeSharedImages']);
    Route::post('/removeSharedImage/{userId}/{imageId}', [ShareController::class, 'removeSharedImage']);
    Route::get('/ifImageShared/{userId}/{imageId}', [ShareController::class, 'ifImageShared']);

});

// Route::get('/login', function(){
//     return view('login');
// })->name('login');
// Route::get('/signup', function(){
//     return view('signup');
// })->name('signup');

require __DIR__.'/auth.php';
