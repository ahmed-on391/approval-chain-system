<?php

use App\Models\User;
use App\Models\Ticket;
use Illuminate\Support\Facades\Route;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use App\Http\Controllers\RoadMap\DataController;
use App\Http\Controllers\Auth\OidcAuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ApprovalChainController;
use Filament\Http\Middleware\Authenticate;
use App\Http\Controllers\ColorController;


// Share ticket
Route::get('/tickets/share/{ticket:code}', function (Ticket $ticket) {
    return redirect()->to(route('filament.resources.tickets.view', $ticket));
})->name('filament.resources.tickets.share');

// Validate an account
Route::get('/validate-account/{user:creation_token}', function (User $user) {
    return view('validate-account', compact('user'));
})
    ->name('validate-account')
    ->middleware([
        'web',
        DispatchServingFilamentEvent::class
    ]);

// Login default redirection
Route::redirect('/login-redirect', '/login')->name('login');

// Road map JSON data
Route::get('road-map/data/{project}', [DataController::class, 'data'])
    ->middleware(['verified', 'auth'])
    ->name('road-map.data');

Route::name('oidc.')
    ->prefix('oidc')
    ->group(function () {
        Route::get('redirect', [OidcAuthController::class, 'redirect'])->name('redirect');
        Route::get('callback', [OidcAuthController::class, 'callback'])->name('callback');
    });


    Route::middleware(['auth'])->group(function () {
        Route::get('/projects', [ProjectController::class, 'index'])->name('filament.resources.projects.index');
    });
        
// route with Approval


Route::prefix('projects')->group(function () {
    Route::get('/', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/{projectId}/approval-chain', [ProjectController::class, 'showApprovalChain'])->name('projects.approval-chain');
    Route::post('/{project}/approval-chain', [ApprovalChainController::class, 'create']);
    Route::post('/{project}/approval-chain/approve', [ApprovalChainController::class, 'approve']);
    Route::post('/{projectId}/approve', [ApprovalChainController::class, 'approve'])->name('projects.approval-chain.approve');
    Route::post('/{projectId}/reject', [ApprovalChainController::class, 'reject'])->name('projects.approval-chain.reject');
});

Route::middleware([Authenticate::class])->group(function () {
    Route::get('/admin', function () {
        return redirect()->route('filament.pages.dashboard');
    });
});

Route::get('/color', [ColorController::class, 'show']);


// Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');

// Route::get('/projects/{id}/approval-chain', [ProjectController::class, 'showApprovalChain'])->name('projects.approval-chain');

// Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
// Route::get('/projects/{id}/approval-chain', [ProjectController::class, 'showApprovalChain'])->name('projects.approval-chain');



// Route::get('/projects/{project}/approval-chain', [ProjectController::class, 'showApprovalChain'])->name('projects.approval-chain');

// Route::get('/projects/{project_id}/approval-chain', [ProjectController::class, 'getApprovalChain']);



Route::get('/projects', [ProjectController::class, 'index']);

Route::post('/projects/{project}/approval-chain', [ApprovalChainController::class, 'create']);


Route::post('/projects/{project}/approval-chain/approve', [ApprovalChainController::class, 'approve']);