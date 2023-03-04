<?php











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

Route::resource('post', 'PostController');
Route::resource('post-info', 'PostInfoController');
Route::resource('lango', 'LangoController');
Route::resource('testing', 'TestingController');
Route::resource('hon', 'HonController');
Route::resource('hons', 'HonsController');
Route::resource('posting', 'PostingController');
Route::resource('postini', 'PostiniController');
Route::resource('postini', 'PostiniController');
Route::resource('postini', 'PostiniController');
Route::resource('postini', 'PostiniController');
Route::resource('postini', 'PostiniController');
Route::resource('postini', 'PostiniController');
Route::resource('post', 'PostController');
Route::resource('post', 'PostController');
Route::resource('post', 'PostController');
Route::resource('post', 'PostController');
Route::resource('post', 'PostController');
Route::resource('post', PostController::class);
Route::resource('post', PostController::class);
Route::get('/post/status_change/post', [PostController::class, 'status_change'])->name('post.status_change');
Route::get('/post/data', [PostController::class, 'data'])->name('post.data');
Route::resource('post', PostController::class);
Route::get('/post/status_change/post', [PostController::class, 'status_change'])->name('post.status_change');
Route::get('/post/data', [PostController::class, 'data'])->name('post.data');
Route::resource('post', PostController::class);
Route::get('/post/status_change/post', [PostController::class, 'status_change'])->name('post.status_change');
Route::get('/post/data', [PostController::class, 'data'])->name('post.data');
Route::resource('post', PostController::class);

Route::get('/post/status_change/post', [PostController::class, 'status_change'])->name('post.status_change');
Route::get('/post/data', [PostController::class, 'data'])->name('post.data');
Route::resource('post', PostController::class);

Route::get('/post/status_change/post', [PostController::class, 'status_change'])->name('post.status_change');
Route::get('/post/data', [PostController::class, 'data'])->name('post.data');
Route::resource('post', PostController::class);

Route::get('/post/status_change/post', [PostController::class, 'status_change'])->name('post.status_change');
Route::get('/post/data', [PostController::class, 'data'])->name('post.data');
Route::resource('post', PostController::class);

Route::get('/post/status_change/post', [PostController::class, 'status_change'])->name('post.status_change');
Route::get('/post/data', [PostController::class, 'data'])->name('post.data');
Route::resource('post', PostController::class);

Route::get('/post/status_change/post', [PostController::class, 'status_change'])->name('post.status_change');
Route::get('/post/data', [PostController::class, 'data'])->name('post.data');
Route::resource('post', PostController::class);

Route::get('/post/status_change/post', [PostController::class, 'status_change'])->name('post.status_change');
Route::get('/post/data', [PostController::class, 'data'])->name('post.data');
Route::resource('post', PostController::class);
Route::get('/post/status_change/post', [PostController::class, 'status_change'])->name('post.status_change');
Route::get('/post/data', [PostController::class, 'data'])->name('post.data');
Route::resource('post', PostController::class);

Route::get('/post/status_change/post', [PostController::class, 'status_change'])->name('post.status_change');
Route::get('/post/data', [PostController::class, 'data'])->name('post.data');
Route::resource('post', PostController::class);

Route::get('/post/status_change/post', [PostController::class, 'status_change'])->name('post.status_change');
Route::get('/post/data', [PostController::class, 'data'])->name('post.data');
Route::resource('post', PostController::class);
Route::get('/post/status_change/post', [PostController::class, 'status_change'])->name('post.status_change');
Route::get('/post/data', [PostController::class, 'data'])->name('post.data');
Route::resource('post', PostController::class);
Route::get('/post/status_change/post', [PostController::class, 'status_change'])->name('post.status_change');
Route::get('/post/data', [PostController::class, 'data'])->name('post.data');
Route::resource('post', PostController::class);
Route::get('/post-economy/status_change/post-economy', [PostEconomyController::class, 'status_change'])->name('post-economy.status_change');
Route::get('/post-economy/data', [PostEconomyController::class, 'data'])->name('post-economy.data');
Route::resource('post-economy', PostEconomyController::class);
Route::get('/category/status_change/category', [CategoryController::class, 'status_change'])->name('category.status_change');
Route::get('/category/data', [CategoryController::class, 'data'])->name('category.data');
Route::resource('category', CategoryController::class);