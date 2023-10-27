<?php

use App\Http\Controllers\RejectPartnerController;
use App\Http\Controllers\RequestPartnerController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\UpdatePartnerController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::resource('/country', 'CountryController');
    Route::resource('/state', 'StateController');
    Route::resource('/city', 'CityController');
    Route::resource('/township', 'TownshipController');
    Route::resource('/ward', 'WardController');
    Route::resource('/street', 'StreetController');
    Route::resource('/address', 'AddressController');
    Route::resource('/common', 'CommonController');
    Route::resource('/type', 'TypeController');
    Route::resource('/department', 'DepartmentController');
    Route::resource('/roles', RoleController::class);
    Route::resource('/role_permission', RolePermissionController::class);
    Route::resource('/update_partner', UpdatePartnerController::class);
    Route::resource('/reject_partner', RejectPartnerController::class);
});

//Route::middleware(['auth:sanctum'])->group(function () {
Route::post('/login', 'AuthController@login')->name('login');
Route::post('/logout', 'AuthController@logout')->name('logout');
Route::get('/email_verify', 'PasswordResetController@verify')->name('verify');

Route::resource('/platform_user', 'PlatformUserController');
Route::put('/image/{platform_user}', 'ImageUpdateController@update')->name('image.update'); //testing
Route::resource('/request_partner', RequestPartnerController::class);
Route::resource('/event', 'EventController');
Route::resource('/venue', 'VenueController');
Route::resource('/booking', 'BookingController');
Route::resource('/payment', 'PaymentController');
Route::resource('/venues.venue_ratings', 'VenueRatingController')->scoped(['venue_rating' => 'id']);
Route::resource('/venues.venue_comments', 'VenueCommentController')->scoped(['venue_comment' => 'id']);
Route::resource('/adhoc', 'AdHocController');
Route::resource('/qr_ticket', 'QrTicketController');
//});

Route::post('/payment', 'PaymentController@create')->name('payment.store');
Route::get('/success', 'PaymentController@success')->name('success');
Route::get('/cancel', 'PaymentController@cancel')->name('cancel');

Route::post('/password/email', 'PasswordResetController@sendResetLinkEmail')->name('password.email');
Route::get('/password/reset/{token}', 'PasswordResetController@showResetForm')->name('password.reset');
Route::post('/password/reset', 'PasswordResetController@reset')->name('password.update');
Route::post('/contact_us', 'ContactUsController@submitContactUsForm');

//Route::get('/venue/{imageId}/destroy', 'VenueController@destroyImage');//testing image delete
//Route::get('/venue', 'VenueController@index');
//Route::get('/qrgenerate/{event}', 'QrTicketController@show'); //testing qr code image
//Route::get('/home', 'HomeController@index')->name('home');
//Route::resource('/platform_user', 'PlatformUserController')->only('store');