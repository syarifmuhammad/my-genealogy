<?php

declare(strict_types=1);

use App\Http\Controllers\Back\PageController;
use App\Http\Controllers\Back\PeopleController;
use App\Http\Controllers\Back\TeamController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Front\PageController as FrontPageController;

// -----------------------------------------------------------------------------------
// frontend routes
// -----------------------------------------------------------------------------------
Route::get('/', [FrontPageController::class, 'home'])->name('home');
Route::get('password-generator', [FrontPageController::class, 'passwordGenerator'])->name('password.generator');
Route::get('about', [FrontPageController::class, 'about'])->name('about');
Route::get('help', [FrontPageController::class, 'help'])->name('help');

// -----------------------------------------------------------------------------------
// backend routes
// -----------------------------------------------------------------------------------
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function (): void {
    // -----------------------------------------------------------------------------------
    // teams
    // -----------------------------------------------------------------------------------
    Route::get('team', [TeamController::class, 'team'])->name('team');
    Route::get('teamlog', [TeamController::class, 'teamlog'])->name('teamlog');
    Route::get('peoplelog', [TeamController::class, 'peoplelog'])->name('peoplelog');

    Route::put('/teams/{team}/transfer-ownership', [TeamController::class, 'transferOwnership'])->name('teams.transfer-ownership');

    // -----------------------------------------------------------------------------------
    // pages
    // -----------------------------------------------------------------------------------
    Route::get('test', [PageController::class, 'test'])->name('test');

    // -----------------------------------------------------------------------------------
    // people
    // -----------------------------------------------------------------------------------
    Route::get('search', [PeopleController::class, 'search'])->name('people.search');
    Route::get('birthdays', [PeopleController::class, 'birthdays'])->name('people.birthdays');

    Route::get('people/add', [PeopleController::class, 'add'])->name('people.add');
    Route::get('people/{person}', [PeopleController::class, 'show'])->name('people.show');
    Route::get('people/{person}/ancestors', [PeopleController::class, 'ancestors'])->name('people.ancestors');
    Route::get('people/{person}/descendants', [PeopleController::class, 'descendants'])->name('people.descendants');
    Route::get('people/{person}/chart', [PeopleController::class, 'chart'])->name('people.chart');
    Route::get('people/{person}/history', [PeopleController::class, 'history'])->name('people.history');
    Route::get('people/{person}/datasheet', [PeopleController::class, 'datasheet'])->name('people.datasheet');
    Route::get('people/{person}/timeline', [PeopleController::class, 'timeline'])->name('people.timeline');
    Route::get('people/{person}/add-father', [PeopleController::class, 'addFather'])->name('people.add-father');
    Route::get('people/{person}/add-mother', [PeopleController::class, 'addMother'])->name('people.add-mother');
    Route::get('people/{person}/add-child', [PeopleController::class, 'addChild'])->name('people.add-child');
    Route::get('people/{person}/add-partner', [PeopleController::class, 'addPartner'])->name('people.add-partner');
    Route::get('people/{person}/edit-contact', [PeopleController::class, 'editContact'])->name('people.edit-contact');
    Route::get('people/{person}/edit-death', [PeopleController::class, 'editDeath'])->name('people.edit-death');
    Route::get('people/{person}/edit-events', [PeopleController::class, 'editEvents'])->name('people.edit-events');
    Route::get('people/{person}/edit-family', [PeopleController::class, 'editFamily'])->name('people.edit-family');
    Route::get('people/{person}/edit-files', [PeopleController::class, 'editFiles'])->name('people.edit-files');
    Route::get('people/{person}/edit-photos', [PeopleController::class, 'editPhotos'])->name('people.edit-photos');
    Route::get('people/{person}/edit-profile', [PeopleController::class, 'editProfile'])->name('people.edit-profile');
    Route::get('people/{person}/{couple}/edit-partner', [PeopleController::class, 'editPartner'])->name('people.edit-partner');

    // -----------------------------------------------------------------------------------
    // gedcom
    // -----------------------------------------------------------------------------------
    Route::controller(App\Http\Controllers\Back\GedcomController::class)->prefix('gedcom')->as('gedcom.')->group(function (): void {
        Route::get('exportteam', 'exportteam')->name('exportteam');
        Route::get('importteam', 'importteam')->name('importteam');
    });

    // -----------------------------------------------------------------------------------
    // developer
    // -----------------------------------------------------------------------------------
    Route::middleware(App\Http\Middleware\IsDeveloper::class)->prefix('developer')->as('developer.')->group(function (): void {
        // -----------------------------------------------------------------------------------
        // pages
        // -----------------------------------------------------------------------------------
        Route::controller(App\Http\Controllers\Back\DeveloperController::class)->group(function (): void {
            Route::get('settings', 'settings')->name('settings');

            Route::get('teams', 'teams')->name('teams');
            Route::get('people', 'people')->name('people');
            Route::get('users', 'users')->name('users');

            Route::get('dependencies', 'dependencies')->name('dependencies');
            Route::get('session', 'session')->name('session');

            Route::get('userlog/log', 'userlogLog')->name('userlog.log');
            Route::get('userlog/origin', 'userlogOrigin')->name('userlog.origin');
            Route::get('userlog/originmap', 'userlogOriginMap')->name('userlog.origin-map');
            Route::get('userlog/period', 'userlogPeriod')->name('userlog.period');
        });

        // -----------------------------------------------------------------------------------
        // backups
        // -----------------------------------------------------------------------------------
        Route::get('backups', App\Livewire\Backups\Manage::class)->name('backups');
    });
});

// -----------------------------------------------------------------------------------
// set application language in session
// actual language switching wil be handled by App\Http\Middleware\Localization::class
// -----------------------------------------------------------------------------------
Route::get('language/{locale}', function ($locale) {
    session()->put('locale', $locale);

    return back();
});
