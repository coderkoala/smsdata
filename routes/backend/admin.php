<?php

use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\smsController;
use App\Http\Controllers\Backend\ContactController;
use App\Http\Controllers\Backend\CategoryController;
use Tabuna\Breadcrumbs\Trail;

// Administration Dashboard.
Route::redirect('/', '/admin/dashboard', 301);
Route::get('dashboard', [DashboardController::class, 'index'])
    ->name('dashboard')
    ->breadcrumbs(function (Trail $trail) {
        $trail->push(__('Comptech International SMS Management Home'), route('admin.dashboard'));
    });

// Send SMS.
Route::get('sms', [smsController::class, 'index'])
    ->name('sms')
    ->breadcrumbs(function (Trail $trail) {
        $trail->push(__('SMS Management Console'), route('admin.sms'));
    });

// New dispatch view.
Route::get('new-sms', [smsController::class, 'add'])
    ->name('new-sms')
    ->breadcrumbs(function (Trail $trail) {
        $trail->push(__('SMS Management Console'), route('admin.new-sms'));
    });

// New dispatch view.
Route::get('new-sms-bulk', [smsController::class, 'bulk'])
    ->name('new-sms-bulk')
    ->breadcrumbs(function (Trail $trail) {
        $trail->push(__('Send Bulk SMS'), route('admin.new-sms-bulk'));
    });

// New dispatch request.
Route::post('new-sms', [smsController::class, 'post'])
    ->name('post-sms')
    ->breadcrumbs(function (Trail $trail) {
        $trail->push(__('SMS Management Console'), route('admin.post-sms'));
    });

// New dispatch request.
Route::post('new-sms-bulk', [smsController::class, 'postBulk'])
    ->name('post-sms-bulk')
    ->breadcrumbs(function (Trail $trail) {
        $trail->push(__('SMS Management Console'), route('admin.post-sms-bulk'));
    });

// Contact Index.
Route::get('contacts', [ContactController::class, 'index'])
    ->name('contact')
    ->breadcrumbs(function (Trail $trail) {
        $trail->push(__('Contacts Management'), route('admin.contact'));
    });

// Add new Contact.
Route::get('contact/add1', [ContactController::class, 'add'])
    ->name('add-contact')
    ->breadcrumbs(function (Trail $trail) {
        $trail->push(__('Add New Contact'), route('admin.add-contact'));
    });

Route::post('contact/add', [ContactController::class, 'post'])
    ->name('store-contact');

Route::get('contact/addM', [ContactController::class, 'bulkAdd'])
    ->name('bulkAdd');

Route::post('contact/addM', [ContactController::class, 'storebulk'])
    ->name('storeBulkAdd');

// Edit/View Contact.
Route::get('contacts/edit/{id}', [ContactController::class, 'edit'])
    ->name('edit-contact')
    ->breadcrumbs(function (Trail $trail) {
        $trail->push(__('Editing Contact'), route('admin.edit-contact', 0));
    });

Route::post('contacts/edit/{id}', [ContactController::class, 'patch'])
    ->name('edit-patch')
    ->breadcrumbs(function (Trail $trail) {
        $trail->push(__('Editing Contact'), route('admin.edit-patch', 0));
    });

Route::get('contacts/view/{id}', [ContactController::class, 'view'])
    ->name('view-contact')
    ->breadcrumbs(function (Trail $trail) {
        $trail->push(__('Editing Contact'), route('admin.view-contact', 0));
    });

// Contact Categories.
Route::get('category', [CategoryController::class, 'index'])
    ->name('category')
    ->breadcrumbs(function (Trail $trail) {
        $trail->push(__('Category Management'), route('admin.category'));
    });

Route::post('category', [CategoryController::class, 'post'])
    ->name('category-post')
    ->breadcrumbs(function (Trail $trail) {
        $trail->push(__('Category Management'), route('admin.category-post'));
    });

Route::post('delete-category/{id}', [CategoryController::class, 'delete'])
    ->name('delete-category')
    ->breadcrumbs(function (Trail $trail) {
        $trail->push(__('Category Management'), route('admin.delete-category'));
    });
