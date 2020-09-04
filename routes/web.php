<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
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
//

Route::get('/', function () {
    return view('dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('api');

Route::get('/register', function () {
    return view('/register');
});

Route::get('/login', function () {
    return view('/login');
})->name('login');

Route::get('/create', function () {
    $category = App\Category::all();
    return view('/createLink')->with('category', $category);
});

Route::get('/shared/{id}', function(Request $request){
//    dd($request->id);

        $sharedLink = App\Site::where('id',$request->id)
            ->where('access_type',1)
            ->with(['category'=>function($query) {
                $query->select('cat_id', 'category_name')->first();
            }])
            ->get();

        if($sharedLink && $sharedLink->count() >0) {

            return view('dashboard',compact('sharedLink'));
        }

        return view('dashboard')->with('sharedLink', null);
});
