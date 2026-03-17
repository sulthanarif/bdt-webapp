<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\AgendaRegistrationController;
use App\Http\Controllers\Admin\ContentPageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\AgendaEventController;
use App\Http\Controllers\Admin\LandingContentController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\MembershipTypeController;
use App\Http\Controllers\Admin\CampaignController;
use App\Http\Controllers\Admin\VisitController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\TransactionMembershipController;
use App\Http\Controllers\Admin\TransactionVisitController;
use App\Http\Controllers\Admin\TransactionEventController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\MembershipRegistrationController;
use App\Http\Controllers\EventRegistrationController;
use App\Http\Controllers\PublicContentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make somzzething great!
|
*/

Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/events/{event}/register', [EventRegistrationController::class, 'create'])->name('events.register');
Route::post('/events/{event}/register', [EventRegistrationController::class, 'store'])->name('events.register.store');
Route::get('/privacy', [PublicContentController::class, 'privacy'])->name('privacy');
Route::get('/terms', [PublicContentController::class, 'terms'])->name('terms');
Route::get('/faq', [PublicContentController::class, 'faq'])->name('faq');
Route::get('/daftar', function () {
    $url = '/?tab=pricing';
    if (request()->filled('membership')) {
        $url .= '&membership=' . request()->string('membership')->value();
    }

    return redirect($url);
})->name('public.register');
Route::post('/daftar-membership', [MembershipRegistrationController::class, 'store'])->name('public.membership.register');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('login', [AuthController::class, 'login'])->name('login.store');
    });

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('dashboard', [DashboardController::class, 'index']);

        Route::resource('events', AgendaEventController::class)->except(['show']);
        Route::get('events/{event}/registrations', [AgendaRegistrationController::class, 'index'])->name('events.registrations');

        Route::prefix('content')->name('content.')->group(function () {
            Route::get('/', function () {
                return view('admin.content.index');
            })->name('index');
            Route::get('landing', [LandingContentController::class, 'edit'])->name('landing.edit');
            Route::put('landing', [LandingContentController::class, 'update'])->name('landing.update');
            Route::get('pages/{slug}/edit', [ContentPageController::class, 'edit'])->name('pages.edit');
            Route::put('pages/{slug}', [ContentPageController::class, 'update'])->name('pages.update');
            Route::resource('faqs', FaqController::class)->except(['show']);
        });

        Route::resource('campaigns', CampaignController::class);

        Route::prefix('membership')->name('membership.')->group(function () {
            Route::get('/', function () {
                return redirect()->route('admin.membership.types.index');
            })->name('index');
            Route::resource('types', MembershipTypeController::class)->except(['show']);
            Route::resource('members', MemberController::class)->except(['show', 'destroy']);
            Route::get('visits/online', [VisitController::class, 'indexOnline'])->name('visits.online');
            Route::resource('visits', VisitController::class)->only(['index', 'create', 'store']);
        });

        Route::prefix('transactions')->name('transactions.')->group(function () {
            Route::get('/', [TransactionController::class, 'index'])->name('index');
            Route::get('memberships', [TransactionMembershipController::class, 'index'])->name('memberships');
            Route::get('visits', [TransactionVisitController::class, 'index'])->name('visits');
            Route::get('events', [TransactionEventController::class, 'index'])->name('events');
            Route::get('{transaction}', [TransactionController::class, 'show'])->name('show');
        });

        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    });
});
