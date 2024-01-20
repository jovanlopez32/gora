<?php

use App\Http\Controllers\FormController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\TagController;
use App\Models\CountReport;
use App\Models\ProblemReport;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
    return redirect()->route('login');
});

Route::get('/dashboard', function () {

    $count_reports = CountReport::latest()->take(20)->get();
    $problem_reports = ProblemReport::latest()->take(20)->get();
    // Combinar las colecciones utilizando merge
    $merged_reports = $count_reports->merge($problem_reports);

    // Ordenar la colección combinada por fechas (created_at) de manera descendente
    $data = $merged_reports->sortByDesc('created_at')->values();

    return Inertia::render('Dashboard', compact('data'));
})->middleware(['auth', 'verified'])->name('dashboard');


/* Settings */
Route::middleware('auth')->group(function () {
    //Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/configuracion', [SettingsController::class, 'settings'])->name('gora.settings');

    //Técnico
    Route::post('/addnewtechnical', [SettingsController::class, 'addTechnical'])->name('gora.settings.add.technical'); //crear
    Route::put('/edittechnical/{id}', [SettingsController::class, 'editTechnical'])->name('gora.settings.edit.technical'); //editar

    //Sector
    Route::post('/addnewsector', [SettingsController::class, 'addSector'])->name('gora.settings.add.sector'); //crear
    Route::put('/editsector/{id}', [SettingsController::class, 'editSector'])->name('gora.settings.edit.sector'); //editar

    //Equipo
    Route::post('/addnewdevice', [SettingsController::class, 'addDevice'])->name('gora.settings.add.device'); //crear
    Route::put('/editdevice/{id}', [SettingsController::class, 'editDevice'])->name('gora.settings.edit.device'); //editar

    //EndDay
    Route::put('editendday', [SettingsController::class, 'editEndDay'])->name('gora.settings.edit.day');
});

/* Tags */
Route::middleware('auth')->group(function () {
    //Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/administradordeetiquetas', [TagController::class, 'tags'])->name('gora.tags');

    Route::post('/addtag', [TagController::class, 'addTag'])->name('gora.add.tag');
    Route::put('/edittag/{id}', [TagController::class, 'editTag'])->name('gora.edit.tag');
});

/* Technical Form */
Route::middleware('web')->group(function () {
    #Checa si el qr es correcto
    Route::get('/gora/formulariotecnico/qr/{qr_code}', [FormController::class, 'passTechnical'])->name('gora.technical.check.qr');

    #Ingresa la clave y checa que el qr sea correcto
    Route::get('/gora/tecnico', [FormController::class, 'checkTechnicalForm'])->name('gora.technical.form.key');
    #Checa los datos y si todos son correctos los lleva al formulario tecnico.
    Route::post('/gora/checktechnicalkey', [FormController::class, 'checkTechnicalKey'])->name('gora.technical.check.key');

    #Formulario Tecnico
    Route::get('/gora/formulariotecnico/{tag}/{type}/{id}', [FormController::class, 'form'])->name('gora.technical.form');

    #Muestra error si el qr es incorrecto
    Route::get('/gora/error', [FormController::class, 'error'])->name('gora.technical.qr.error');

    #Muestra que los datos fueron enviados con exito.
    Route::get('/gora/datosguardados', [FormController::class, 'success'])->name('gora.technical.success');

    #Ingresar reporte del contador
    Route::post('/savecountreport', [FormController::class, 'saveCountReport'])->name('gora.technical.save.count.report');
    #Ingresa reporte de problema
    Route::post('/saveproblemreport', [FormController::class, 'saveProblemReport'])->name('gora.technical.save.problem.report');
});


/* Reports */
Route::middleware('auth')->group(function () {
    Route::get('/reportes/contadores', [ReportController::class, 'countReport'])->name('gora.reports.counters');
    Route::get('/reportes/problemastecnicos', [ReportController::class, 'problemReport'])->name('gora.reports.problems');

    /* Para búsqueda de reportes */
    Route::post('/buscarreportes', [ReportController::class, 'searchReports'])->name('gora.search.reports');

    /* Para listar reportes*/
    Route::get('/reportes/contadoresalterno', [ReportController::class, 'countReportAltern'])->name('gora.reports.countersaltern');

    /* Para limpiar filtros de búsqueda */
    Route::post('/limpiarfiltrosreportes', [ReportController::class, 'cleanSearchReports'])->name('gora.clean.searchreports');

});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
