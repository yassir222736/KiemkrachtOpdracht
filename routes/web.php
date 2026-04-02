<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\Admin\TicketController as AdminTicketController;

/*
|--------------------------------------------------------------------------
| Publieke Routes
|--------------------------------------------------------------------------
*/

Route::get('/', fn () => redirect()->route('tickets.create'));

Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');

/*
|--------------------------------------------------------------------------
| Authenticatie Routes (via laravel/ui)
|--------------------------------------------------------------------------
| Registratie uitgeschakeld — admin-accounts worden aangemaakt via seeders.
*/

Auth::routes([
    'register' => false,
    'reset'    => false,
    'verify'   => false,
]);

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
| Beschermd door 'auth' (ingelogd) en 'admin' (beheerder) middleware.
*/

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'admin'])
    ->group(function () {
        Route::get('/', fn () => redirect()->route('admin.tickets.index'))->name('dashboard');
        Route::get('/tickets', [AdminTicketController::class, 'index'])->name('tickets.index');
        Route::get('/tickets/{ticket}', [AdminTicketController::class, 'show'])->name('tickets.show');
        Route::get('/tickets/{ticket}/edit', [AdminTicketController::class, 'edit'])->name('tickets.edit');
        Route::put('/tickets/{ticket}', [AdminTicketController::class, 'update'])->name('tickets.update');
        Route::get('/tickets/{ticket}/receipt', [AdminTicketController::class, 'downloadReceipt'])->name('tickets.receipt');
    });
