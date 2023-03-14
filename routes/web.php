<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SearchController;

use App\Http\Controllers\PostController;
Route::get('/', [HomeController::class,'index']);
Route::get('/terms-and-conditions', [HomeController::class,'term']);
Route::get('/search',[SearchController::class, 'search'])->name('web.search');
Route::get('/find',[SearchController::class, 'find'])->name('web.find');
Route::get('/post',[PostController::class, 'search1'])->name('web.search');
Route::get('/posts',[PostController::class, 'find1'])->name('web.find1');

Route::prefix('/panel')->group(function(){
  
    Route::get('/npage', [AdminController::class, 'npage']);
    Route::post('npage', [AdminController::class, 'npageAction']);
   
    Route::get('/', [AdminController::class, 'index']);
    Route::get('/{slug}/links', [AdminController::class, 'pageLinks']);
    Route::get('/{slug}/stats', [AdminController::class, 'pageStats']);
    Route::get('/{slug}/design', [AdminController::class, 'pageDesign']);
    Route::post('/{slug}/design', [AdminController::class, 'pageDesignAction']);
    Route::get('/{slug}/stats', [AdminController::class, 'pageStats']);
    Route::get('/linkorder/{linkid}/{pos}', [AdminController::class, 'linkOrderUpdate']);
    Route::get('/{slug}/newlink', [AdminController::class, 'newLink']);
    Route::post('/{slug}/newlink', [AdminController::class, 'newLinkAction']);
    Route::get('/{slug}/newvideo', [AdminController::class, 'newVideo']);
    Route::post('/{slug}/newvideo', [AdminController::class, 'newVideoAction']);
    
      Route::get('/{slug}/newpost', [AdminController::class, 'newPost']);
    Route::post('/{slug}/newpost', [AdminController::class, 'newPostAction']);
    Route::get('/{slug}/editpost/{linkid}', [AdminController::class, 'editPost']);
    Route::post('/{slug}/editpost/{linkid}', [AdminController::class, 'editPostAction']);
    Route::get('/{slug}/newpostvideo', [AdminController::class, 'newPostVideo']);
    Route::post('/{slug}/newpostvideo', [AdminController::class, 'newPostVideoAction']);
    
        Route::get('/{slug}/editpostvideo/{linkid}', [AdminController::class, 'editPostVideo']);
    Route::post('/{slug}/editpostvideo/{linkid}', [AdminController::class, 'editPostVideoAction']);
    
    Route::get('/{slug}/newaudio', [AdminController::class, 'newAudio']);
    Route::post('/{slug}/newaudio', [AdminController::class, 'newAudioAction']);
    Route::get('/{slug}/editlink/{linkid}', [AdminController::class, 'editLink']);
    Route::post('/{slug}/editlink/{linkid}', [AdminController::class, 'editLinkAction']);
    Route::get('/{slug}/dellink/{linkid}', [AdminController::class, 'delLink']);
     Route::get('/{slug}/editvideo/{linkid}', [AdminController::class, 'editVideo']);
    Route::post('/{slug}/editvideo/{linkid}', [AdminController::class, 'editVideoAction']);
     Route::get('/{slug}/editaudio/{linkid}', [AdminController::class, 'editAudio']);
    Route::post('/{slug}/editaudio/{linkid}', [AdminController::class, 'editAudioAction']);

});
Route::get('/{slug}', [PageController::class,'index']);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
