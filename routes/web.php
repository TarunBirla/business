<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PublicGroupController;
use App\Http\Controllers\PublicEventController;
use App\Http\Controllers\CmsController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Member\MemberDashboardController;
use App\Http\Controllers\Member\MemberProfileController;
use App\Http\Controllers\Member\MemberDirectoryController;
use App\Http\Controllers\Member\ConnectionController;
use App\Http\Controllers\Member\EventRegistrationController;
use App\Http\Controllers\Member\MessageController;
use App\Http\Controllers\GroupAdmin\GroupAdminDashboardController;
use App\Http\Controllers\GroupAdmin\GroupAdminMemberController;
use App\Http\Controllers\GroupAdmin\GroupAdminEventController;
use App\Http\Controllers\GroupAdmin\GroupAdminNoticeController;
use App\Http\Controllers\GroupAdmin\GroupAdminPromotionController;
use App\Http\Controllers\SuperAdmin\SuperAdminDashboardController;
use App\Http\Controllers\SuperAdmin\SuperAdminGroupController;
use App\Http\Controllers\SuperAdmin\SuperAdminUserController;
use App\Http\Controllers\SuperAdmin\SuperAdminCmsController;
use App\Http\Controllers\SuperAdmin\ThemeController;
use App\Http\Controllers\SuperAdmin\SuperAdminProjectController;

use App\Http\Controllers\PublicBusinessCardController;
use App\Http\Controllers\Member\MemberProjectController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', fn() => redirect()->route('member.dashboard'));
Route::get('/groups', [PublicGroupController::class, 'index'])->name('groups.index');
Route::get('/groups/{slug}', [PublicGroupController::class, 'show'])->name('groups.show');
Route::get('/join/{slug}', [PublicGroupController::class, 'show'])->name('groups.join');
Route::get('/groups/{slug}/qr', [PublicGroupController::class, 'qr'])->name('groups.qr');
Route::match(['get', 'post'], '/groups/{group}/member-join', [PublicGroupController::class, 'joinCommunity'])->name('groups.member_join')->middleware('auth');

Route::get('/events', [PublicEventController::class, 'index'])->name('events.index');
Route::get('/events/{slug}', [PublicEventController::class, 'show'])->name('events.show');

Route::get('/page/{slug}', [CmsController::class, 'show'])->name('cms.show');

// Public Digital Business Card & vCard Export
Route::get('/bizcard/{user}', [PublicBusinessCardController::class, 'show'])->name('bizcard.show');
Route::get('/bizcard/{user}/vcard', [PublicBusinessCardController::class, 'downloadVcard'])->name('bizcard.vcard');

/*
|--------------------------------------------------------------------------
| Guest Authentication Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Member Protected Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Checkout for Paid Communities
    Route::get('/join/checkout/{group}', [AuthController::class, 'showCheckout'])->name('join.checkout');
    Route::post('/join/checkout/{group}', [AuthController::class, 'processCheckout'])->name('join.checkout.process');

    // Member Dashboard & Profile
    Route::get('/dashboard', [MemberDashboardController::class, 'index'])->name('member.dashboard');
    Route::get('/member/profile', [MemberProfileController::class, 'edit'])->name('member.profile.edit');
    Route::post('/member/profile', [MemberProfileController::class, 'update'])->name('member.profile.update');
    Route::post('/member/profile/theme', [MemberProfileController::class, 'updateTheme'])->name('member.profile.theme');

    // Member Projects / Portfolio Showcase
    Route::get('/member/projects', [MemberProjectController::class, 'index'])->name('member.projects.index');
    Route::get('/member/projects/create', [MemberProjectController::class, 'create'])->name('member.projects.create');
    Route::post('/member/projects', [MemberProjectController::class, 'store'])->name('member.projects.store');
    Route::get('/member/projects/{project}/edit', [MemberProjectController::class, 'edit'])->name('member.projects.edit');
    Route::put('/member/projects/{project}', [MemberProjectController::class, 'update'])->name('member.projects.update');
    Route::delete('/member/projects/{project}', [MemberProjectController::class, 'destroy'])->name('member.projects.destroy');

    // Directory & Member Profile Viewing
    Route::get('/member/directory', [MemberDirectoryController::class, 'index'])->name('member.directory');
    Route::get('/member/directory/{user}', [MemberDirectoryController::class, 'show'])->name('member.directory.show');

    // Connection System & Contact Requests
    Route::get('/member/connections', [ConnectionController::class, 'index'])->name('member.connections');
    Route::post('/member/connections/send', [ConnectionController::class, 'sendRequest'])->name('member.connections.send');
    Route::match(['get', 'post'], '/member/connections/{connection}/accept', [ConnectionController::class, 'acceptRequest'])->name('member.connections.accept');
    Route::match(['get', 'post'], '/member/connections/{connection}/reject', [ConnectionController::class, 'rejectRequest'])->name('member.connections.reject');
    Route::post('/member/contact-request/send', [ConnectionController::class, 'requestContactDetails'])->name('member.contact_request.send');

    // Event Registration
    Route::post('/events/{event}/register', [EventRegistrationController::class, 'register'])->name('member.events.register');

    // Community Messaging / Chat System
    Route::get('/member/chat/{groupId?}/{receiverId?}', [MessageController::class, 'index'])->name('member.chat');
    Route::post('/member/chat/send', [MessageController::class, 'sendMessage'])->name('member.chat.send');
});

/*
|--------------------------------------------------------------------------
| Group Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'group_admin'])->prefix('group-admin')->name('group_admin.')->group(function () {
    Route::get('/', [GroupAdminDashboardController::class, 'index'])->name('dashboard');

    // Group Member Management
    Route::get('/group/{group}/members', [GroupAdminMemberController::class, 'index'])->name('members.index');
    Route::get('/group/{group}/members/create', [GroupAdminMemberController::class, 'create'])->name('members.create');
    Route::post('/group/{group}/members', [GroupAdminMemberController::class, 'store'])->name('members.store');
    Route::get('/group/{group}/members/export-csv', [GroupAdminMemberController::class, 'exportCsv'])->name('members.export_csv');

    // Group Event Management
    Route::get('/group/{group}/events', [GroupAdminEventController::class, 'index'])->name('events.index');
    Route::get('/group/{group}/events/create', [GroupAdminEventController::class, 'create'])->name('events.create');
    Route::post('/group/{group}/events', [GroupAdminEventController::class, 'store'])->name('events.store');
    Route::get('/group/{group}/events/{event}/attendees', [GroupAdminEventController::class, 'attendees'])->name('events.attendees');
    Route::get('/group/{group}/events/{event}/export-csv', [GroupAdminEventController::class, 'exportAttendeesCsv'])->name('events.export_csv');

    // Group Notice Board
    Route::get('/group/{group}/notices', [GroupAdminNoticeController::class, 'index'])->name('notices.index');
    Route::get('/group/{group}/notices/create', [GroupAdminNoticeController::class, 'create'])->name('notices.create');
    Route::post('/group/{group}/notices', [GroupAdminNoticeController::class, 'store'])->name('notices.store');

    // Promotion & QR Code System
    Route::get('/group/{group}/promotion', [GroupAdminPromotionController::class, 'index'])->name('promotion.index');
});

/*
|--------------------------------------------------------------------------
| Super Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'super_admin'])->prefix('super-admin')->name('super_admin.')->group(function () {
    Route::get('/', [SuperAdminDashboardController::class, 'index'])->name('dashboard');

    // Community Management
    Route::get('/groups', [SuperAdminGroupController::class, 'index'])->name('groups.index');
    Route::get('/groups/create', [SuperAdminGroupController::class, 'create'])->name('groups.create');
    Route::post('/groups', [SuperAdminGroupController::class, 'store'])->name('groups.store');
    Route::get('/groups/{group}/edit', [SuperAdminGroupController::class, 'edit'])->name('groups.edit');
    Route::put('/groups/{group}', [SuperAdminGroupController::class, 'update'])->name('groups.update');

    // User Management
    Route::get('/users', [SuperAdminUserController::class, 'index'])->name('users.index');
    Route::post('/users/{user}/toggle-status', [SuperAdminUserController::class, 'toggleStatus'])->name('users.toggle_status');

    // Member Projects Overview
    Route::get('/projects', [SuperAdminProjectController::class, 'index'])->name('projects.index');

    // CMS Pages Management
    Route::get('/cms', [SuperAdminCmsController::class, 'index'])->name('cms.index');
    Route::get('/cms/{page}/edit', [SuperAdminCmsController::class, 'edit'])->name('cms.edit');
    Route::put('/cms/{page}', [SuperAdminCmsController::class, 'update'])->name('cms.update');

    // Theme Manager
    Route::get('/themes', [ThemeController::class, 'index'])->name('themes.index');
    Route::get('/themes/create', [ThemeController::class, 'create'])->name('themes.create');
    Route::post('/themes', [ThemeController::class, 'store'])->name('themes.store');
    Route::get('/themes/{theme}/edit', [ThemeController::class, 'edit'])->name('themes.edit');
    Route::put('/themes/{theme}', [ThemeController::class, 'update'])->name('themes.update');
    Route::post('/themes/{theme}/set-default', [ThemeController::class, 'setDefault'])->name('themes.set_default');
    Route::post('/themes/{theme}/duplicate', [ThemeController::class, 'duplicate'])->name('themes.duplicate');
    Route::delete('/themes/{theme}', [ThemeController::class, 'destroy'])->name('themes.destroy');
});
