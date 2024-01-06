<?php

use App\Http\Controllers\PipelineController;
use App\Http\Livewire\RTL;
use App\Http\Livewire\Tables;
use App\Http\Livewire\Billing;
use App\Http\Livewire\Profile;
use App\Http\Livewire\ViewEdit;
use App\Http\Livewire\Dashboard;
use App\Http\Livewire\ViewShoot;
use App\Http\Livewire\Auth\Login;
use App\Http\Livewire\StaticSignIn;
use App\Http\Livewire\StaticSignUp;
use App\Http\Livewire\Auth\Register;
use App\Http\Livewire\ClientBooking;
use App\Http\Livewire\CompleteEdits;
use App\Http\Livewire\Notifications;
use App\Http\Livewire\CompleteShoots;
use App\Http\Livewire\PhotoDashboard;
use App\Http\Livewire\VirtualReality;
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\EditorDashboard;
use App\Http\Livewire\Auth\ResetPassword;
use App\Http\Livewire\Auth\ForgotPassword;
use App\Http\Livewire\CompletedPipelines;
use App\Http\Livewire\ExampleLaravel\UserProfile;
use App\Http\Livewire\ExampleLaravel\UserManagement;
use App\Http\Livewire\PipelineOverview;
use App\Http\Livewire\ViewPipeline;

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
    return redirect('sign-in');
});

Route::get('forgot-password', ForgotPassword::class)->middleware('guest')->name('password.forgot');
Route::get('reset-password/{id}', ResetPassword::class)->middleware('signed')->name('reset-password');



Route::get('sign-in', Login::class)->middleware('guest')->name('login');
Route::get('sign-up', Register::class)->middleware('guest')->name('register');
Route::get('client-booking', ClientBooking::class)->middleware('guest')->name('client-booking');
// Route::get('client-booking',[PipelineController::class,'index']);

Route::get('user-profile', UserProfile::class)->middleware('auth')->name('user-profile');

Route::group(['middleware' => 'auth'], function () {
    Route::get('billing', Billing::class)->name('billing');
    Route::get('profile', Profile::class)->name('profile');
    Route::get('tables', Tables::class)->name('tables');
    Route::get('notifications', Notifications::class)->name("notifications");
    Route::get('virtual-reality', VirtualReality::class)->name('virtual-reality');
    Route::get('static-sign-in', StaticSignIn::class)->name('static-sign-in');
    Route::get('static-sign-up', StaticSignUp::class)->name('static-sign-up');
    Route::get('rtl', RTL::class)->name('rtl');
    
    Route::middleware(['checkUserRole:admin'])->group(function () {
        Route::get('dashboard', Dashboard::class)->name('dashboard');
        Route::get('user-management', UserManagement::class)->middleware('auth')->name('user-management');
        Route::get('pipeline-overview', PipelineOverview::class)->name('pipeline-overview');
        Route::get('view-pipeline/{id}', ViewPipeline::class)->name('view-pipeline');
        Route::get('completed-pipelines', CompletedPipelines::class)->name('completed-pipelines');
    });
    
    // editor Routes
    Route::middleware(['checkUserRole:editor'])->group(function () {
        Route::get('editor-dashboard', EditorDashboard::class)->name('editor-dashboard');
        Route::get('view-edit/{id}', ViewEdit::class)->name('view-edit');
        Route::get('complete-edits', CompleteEdits::class)->name('complete-edits');
    });
    
    // photo Routes
    Route::middleware(['checkUserRole:photo'])->group(function () {
        Route::get('photo-dashboard', PhotoDashboard::class)->name('photo-dashboard');
        Route::get('view-shoot/{id}', ViewShoot::class)->name('view-shoot');
        Route::get('complete-shoots', CompleteShoots::class)->name('complete-shoots');
    });
});
