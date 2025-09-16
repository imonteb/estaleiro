<?php

use App\Filament\Resources\DailyTeamResource;
use App\Filament\Resources\DailyTeamResource\Pages\TeamCard;
use App\Http\Controllers\DailyTeamController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OperationsdailyController;
use App\Http\Controllers\TechnicalinfoController;
use App\Http\Controllers\TemplateTeamsController;
use App\Livewire\Admin\TeamWorkbench;
use Illuminate\Support\Facades\Route;
use App\Models\PublishedOperationsDay;
use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// 1. Rutas generales
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/technicalinfo', [TechnicalinfoController::class, 'index'])->name('technicalinfo');


Route::get('/operaciones', function () {
    $publishedDay = PublishedOperationsDay::orderByDesc('date')->first();
    return view('operaciones', compact('publishedDay'));
})->name('operaciones');

Route::post('/operaciones/publish-day', function (Request $request) {
    $request->validate([
        'published_day' => 'required|date',
    ]);
    $publishedDay = PublishedOperationsDay::updateOrCreate(
        ['date' => $request->published_day],
        []
    );
    return redirect()->route('operaciones')->with('success', 'Dia publicado actualizado.');
})->name('operaciones.publish-day');

Route::view('dashboard', 'dashboard')->middleware(['auth', 'verified'])->name('dashboard');
Route::view('profile', 'profile')->middleware(['auth'])->name('profile');

// 2. Rutas de equipos diarios (Livewire)
Route::middleware(['auth', 'role:admin|super_admin'])->group(function () {
    Route::get('/daily-teams', \App\Livewire\DailyTeams\DailyTeamsIndex::class)->name('daily-teams.index');

    // Opcional: Route::get('/equipos-diarios', \App\Livewire\DailyTeams\DailyTeamsIndex::class)->name('equipos-diarios.index');
});

// 3. Rutas de equipos diarios (Filament Resource)
/* Route::middleware(['auth'])->group(function () {
    Route::get('/daily-teams', [DailyTeamResource::class, 'index'])->name('daily-teams.index');
    Route::get('/daily-teams/create', [DailyTeamResource::class, 'create'])->name('daily-teams.create');
    Route::post('/daily-teams', [DailyTeamResource::class, 'store'])->name('daily-teams.store');
    Route::get('/daily-teams/{id}/edit', [DailyTeamResource::class, 'edit'])->name('daily-teams.edit');
    Route::put('/daily-teams/{id}', [DailyTeamResource::class, 'update'])->name('daily-teams.update');
    Route::delete('/daily-teams/{id}', [DailyTeamResource::class, 'destroy'])->name('daily-teams.destroy');
}); */

// 4. Rutas de dashboard y template teams
Route::middleware(['auth', 'permission:templateteampermissions'])->group(function () {
    Route::get('/dashboard/template-teams', [TemplateTeamsController::class, 'index'])->name('dashboard.template-teams');
    Route::view('/dashboard/daily-teams', 'dashboard-daily-teams')->name('dashboard.daily-teams');
});
Route::middleware(['auth', 'role:admin|super_admin'])->group(function () {
    Route::view('/dashboard/publish-work-day', 'dashboard-publish-work-day')->name('dashboard.publish-work-day');
});

// 5. Rutas de error y pruebas
Route::get('/erro-teste', function () {
    abort(500);
});
Route::get('/probar-403', function () {
    abort(403);
});

// Auth routes
require __DIR__ . '/auth.php';


/*
Route::get('/', [HomeController::class, 'index'])
    ->name('home');


Route::get('/technicalinfo', [TechnicalinfoController::class, 'index'])
    ->name('technicalinfo');
Route::get('/operaciones', function () {
    return view('operaciones');

})->name('operaciones');
Route::prefix('daily-teams')->name('daily-teams.')->middleware(['role:admin|super_admin'])->group(function () {
    Route::get('/', \App\Livewire\DailyTeams\DailyTeamsIndex::class)->name('index');
});
Route::middleware(['auth'])->group(function () {
    Route::get('/daily-teams', [DailyTeamResource::class, 'index'])->name('daily-teams.index');
    Route::get('/daily-teams/create', [DailyTeamResource::class, 'create'])->name('daily-teams.create');
    Route::post('/daily-teams', [DailyTeamResource::class, 'store'])->name('daily-teams.store');
    Route::get('/daily-teams/{id}/edit', [DailyTeamResource::class, 'edit'])->name('daily-teams.edit');
    Route::put('/daily-teams/{id}', [DailyTeamResource::class, 'update'])->name('daily-teams.update');
    Route::delete('/daily-teams/{id}', [DailyTeamResource::class, 'destroy'])->name('daily-teams.destroy');
    //Route::get('/team-card', [TeamCard::class, 'render'])->name('team-card');
});

Route::middleware(['auth', 'permission:templateteampermissions'])->group(function () {
    Route::get('/dashboard/template-teams', [TemplateTeamsController::class, 'index'])->name('dashboard.template-teams');
    Route::view('/dashboard/daily-teams', 'dashboard-daily-teams')->name('dashboard.daily-teams');
});

Route::get('/erro-teste', function () {
    abort(500);
});
Route::get('/probar-403', function () {
    abort(403);
});


//Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::get('/equipos-diarios', \App\Livewire\DailyTeams\DailyTeamsIndex::class)->name('daily-teams.index');
 */
// Auth routes
require __DIR__ . '/auth.php';
