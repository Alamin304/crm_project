<?php

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

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\Auth as AuthController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\Clients;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\ContractTypeController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\CreditNoteController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerGroupController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EstimateController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\LeadSourceController;
use App\Http\Controllers\LeadStatusController;
use App\Http\Controllers\Listing;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaymentModeController;
use App\Http\Controllers\PredefinedReplyController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TicketPriorityController;
use App\Http\Controllers\TicketReplyController;
use App\Http\Controllers\TicketStatusController;
use App\Http\Controllers\TranslationManagerController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Web;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CustomerStatementController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\AwardListController;
use App\Http\Controllers\BedController;
use App\Http\Controllers\BookingListController;
use App\Http\Controllers\BookingSourceController;
use App\Http\Controllers\CheckInController;
use App\Http\Controllers\CheckOutController;
use App\Http\Controllers\ComplementaryController;
use App\Http\Controllers\JobCategoryController;
use App\Http\Controllers\JobPostController;
use App\Http\Controllers\NoticeBoardController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\WakeUpCallController;

Route::get('/otp-verify', [OtpController::class, 'showVerifyOtp'])->name('otp.verify');
Route::post('/otp-verify', [OtpController::class, 'verifyOtp'])->name('otp.verify.post');

Route::get('/', function () {
    return Redirect::to('/login');
})->name('redirect.login');

Route::get('download/invoice/{number}', [InvoiceController::class, 'downloadPDF']);

Auth::routes(['verify' => true]);



/** account verification route */
Route::get('activate', [AuthController\RegisterController::class, 'verifyAccount'])->name('activate');

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('articles', [Web\ArticleController::class, 'index']);
Route::get('search-article', [Web\ArticleController::class, 'searchArticle'])->name('article.search');
Route::get('articles/{article}', [Web\ArticleController::class, 'show']);

// Impersonate admin routes
Route::get('/impersonate/{userId}', [MemberController::class, 'impersonate'])->name('impersonate');
Route::get('/impersonate-leave', [MemberController::class, 'impersonateLeave'])->name('impersonate.leave');

// Impersonate customer routes
Route::get('/contacts-impersonate/{userId}', [ContactController::class, 'impersonate'])->name('contacts.impersonate');
Route::get(
    '/contacts-impersonate-leave',
    [ContactController::class, 'impersonateLeave']
)->name('contacts.impersonate.leave');

//Header Notification
Route::get('/get-notifications', [NotificationController::class, 'index']);
Route::post(
    '/notification/{notification}/read',
    [NotificationController::class, 'readNotification']
)->name('notifications.read');
Route::post(
    '/read-all-notification',
    [NotificationController::class, 'readAllNotification']
)->name('notifications.read.all');

Route::middleware(['auth', 'xss', 'checkUserStatus', 'checkRoleUrl', 'super_admin_timeout'])->prefix('admin')->group(function () {
    // Dashboard route

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('dashboard/employees', [DashboardController::class, 'getExpireIdentications'])->name('dashboard.employees');
    Route::get('dashboard/expiry/', [DashboardController::class, 'expireList'])->name('dashboard.expire.list');
    // Customer groups routes
    Route::middleware('permission:manage_customer_groups')->group(function () {
        Route::get('customer-groups', [CustomerGroupController::class, 'index'])->name('customer-groups.index');
        Route::post('customer-groups', [CustomerGroupController::class, 'store'])->name('customer-groups.store');
        Route::get('customer-groups/create', [CustomerGroupController::class, 'create'])->name('customer-groups.create');
        Route::put(
            'customer-groups/{customerGroup}',
            [CustomerGroupController::class, 'update']
        )->name('customer-groups.update');
        Route::get('customer-groups/{customerGroup}', [CustomerGroupController::class, 'show'])->name('customer-groups.show');
        Route::delete(
            'customer-groups/{customerGroup}',
            [CustomerGroupController::class, 'destroy']
        )->name('customer-groups.destroy');
        Route::get(
            'customer-groups/{customerGroup}/edit',
            [CustomerGroupController::class, 'edit']
        )->name('customer-groups.edit');
    });








    Route::group(['middleware' => ['permission:view_branches|create_branches|update_branches|delete_branches']], function () {
        Route::get('branches', [BranchController::class, 'index'])->name('branches.index');
        Route::get('branches/create', [BranchController::class, 'create'])->middleware('permission:create_branches')->name('branches.create');
        Route::post(
            'branches',
            [BranchController::class, 'store']
        )->middleware('permission:create_branches')->name('branches.store');
        Route::get('branches/{branch}/view', [BranchController::class, 'view'])->middleware('permission:view_branches')->name('branches.view');
        Route::get('branches/{branch}/edit', [BranchController::class, 'edit'])->middleware('permission:update_branches')->name('branches.edit');
        Route::put('branches/{branch}', [BranchController::class, 'update'])->middleware('permission:update_branches')->name('branches.update');
        Route::delete('branches/{branch}', [BranchController::class, 'destroy'])->middleware('permission:delete_branches')->name('branches.destroy');
    });





    // Tags module routes
    Route::middleware('permission:manage_tags')->group(function () {
        Route::get('tags', [TagController::class, 'index'])->name('tags.index');
        Route::post('tags', [TagController::class, 'store'])->name('tags.store');
        Route::get('tags/{tag}/edit', [TagController::class, 'edit'])->name('tags.edit');
        Route::put('tags/{tag}', [TagController::class, 'update'])->name('tags.update');
        Route::delete('tags/{tag}', [TagController::class, 'destroy'])->name('tags.destroy');
        Route::get('tags/{tag}', [TagController::class, 'show'])->name('tags.show');
    });

    // Customer routes
    Route::middleware(['permission:view_customers|create_customers|update_customers|delete_customers'])->group(function () {
        Route::get('customers', [CustomerController::class, 'index'])->name('customers.index');
        Route::get('customers/create', [CustomerController::class, 'create'])->middleware('permission:create_customers')->name('customers.create');
        Route::post(
            'customers',
            [CustomerController::class, 'store']
        )->middleware('permission:create_customers')->name('customers.store');
        Route::get('customers/{customer}', [CustomerController::class, 'show'])->middleware('permission:view_customers')->name('customers.show');
        Route::get('customers/{customer}/edit', [CustomerController::class, 'edit'])->middleware('permission:update_customers')->name('customers.edit');
        Route::post('customers/{customer}', [CustomerController::class, 'update'])->middleware('permission:update_customers')->name('customers.update');
        Route::delete('customers/{customer}', [CustomerController::class, 'destroy'])->middleware('permission:delete_customers')->name('customers.destroy');
        Route::get('customers/{customer}/{group}', [CustomerController::class, 'show'])->middleware('permission:view_customers');
        Route::post('customers/{customer}/{group}/notes-count', [CustomerController::class, 'getNotesCount'])->middleware('permission:view_customers');
        Route::get('search-customers', [CustomerController::class, 'searchCustomer'])->middleware('permission:view_customers')->name('customers.search.customer');
        Route::post('add-customer-address', [CustomerController::class, 'addCustomerAddress'])->middleware('permission:update_customers')->name('add.customer.address');
    });


    // Contacts routes
    Route::get('contacts', [ContactController::class, 'index'])->name('contacts.index');
    Route::get('contacts/create/{customerId?}', [ContactController::class, 'create'])->name('contacts.create');
    Route::post('contacts', [ContactController::class, 'store'])->name('contacts.store');
    Route::get('contacts/{contact}', [ContactController::class, 'show'])->name('contacts.show');
    Route::get('contacts/{contact}/edit', [ContactController::class, 'edit'])->name('contacts.edit');
    Route::post('contacts/{contact}', [ContactController::class, 'update'])->name('contacts.update');
    Route::delete('contacts/{contact}', [ContactController::class, 'destroy'])->name('contacts.destroy');
    Route::post(
        'contacts/{contact}/active-deactive',
        [ContactController::class, 'activeDeActiveContact']
    )->name('contacts.activeDeActiveContact');

    // Notes routes
    Route::get('notes', [NoteController::class, 'index'])->name('notes.index');
    Route::post('notes', [NoteController::class, 'store'])->name('notes.store');
    Route::get('notes/{note}/edit', [NoteController::class, 'edit'])->name('notes.edit');
    Route::put('notes/{note}', [NoteController::class, 'update'])->name('notes.update');
    Route::delete('notes/{note}', [NoteController::class, 'destroy'])->name('notes.destroy');

    // Reminders routes
    Route::get('reminder', [ReminderController::class, 'index'])->name('reminder.index');
    Route::post('reminder', [ReminderController::class, 'store'])->name('reminder.store');
    Route::get('reminder/{reminder}/edit', [ReminderController::class, 'edit'])->name('reminder.edit');
    Route::put('reminder/{reminder}', [ReminderController::class, 'update'])->name('reminder.update');
    Route::delete('reminder/{reminder}', [ReminderController::class, 'destroy'])->name('reminder.destroy');

    // Comments routes
    Route::get('comments', [CommentController::class, 'index'])->name('comments.index');
    Route::post('comments', [CommentController::class, 'store'])->name('comments.store');
    Route::put('comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::get('comments/{comment}/edit', [CommentController::class, 'edit'])->name('comments.edit');

    Route::group(['middleware' => ['permission:view_statement|export_statement']], function () {
        Route::get('customer-statement', [CustomerStatementController::class, 'index'])->middleware('permission:view_statement')->name('customer-statement.index');
        Route::get('customer-statements/export', [CustomerStatementController::class, 'export'])
            ->middleware('permission:export_statement')
            ->name('customer-statement.export');
    });










    Route::group(['middleware' => ['permission:view_banks|create_banks|edit_bank|delete_banks']], function () {
        Route::get('banks', [BankController::class, 'index'])->name('banks.index');
        Route::get('banks/{bank}/view', [BankController::class, 'view'])->middleware('permission:view_banks')->name('banks.view');
        Route::get('banks/create', [BankController::class, 'create'])->middleware('permission:create_banks')->name('banks.create');
        Route::post('banks', [BankController::class, 'store'])->middleware('permission:create_banks')->name('banks.store');
        Route::get('banks/{bank}/edit', [BankController::class, 'edit'])->middleware('permission:update_leave_groups')->name('banks.edit');
        Route::put('banks/{bank}', [BankController::class, 'update'])->middleware('permission:update_leave_groups')->name('banks.update');
        Route::delete('banks/{bank}', [BankController::class, 'destroy'])->middleware('permission:delete_leave_groups')->name('banks.destroy');
    });








    Route::group(['middleware' => ['permission:view_accounts|create_accounts|update_accounts|delete_accounts|export_accounts']], function () {
        Route::get(
            'accounts',
            [AccountController::class, 'index']
        )->name('accounts.index');
        Route::get('accounts/{account}/view', [AccountController::class, 'view'])->middleware('permission:view_accounts')->name('accounts.view');
        Route::get('accounts/card/{id?}', [AccountController::class, 'getCardsInfo'])->name('accounts.card');
        Route::get('accounts/create', [AccountController::class, 'create'])->middleware('permission:create_accounts')->name('accounts.create');
        Route::post('accounts', [AccountController::class, 'store'])->middleware('permission:create_accounts')->name('accounts.store');
        Route::get('accounts/{account}/edit', [AccountController::class, 'edit'])->middleware('permission:update_accounts')->name('accounts.edit');
        Route::put('accounts/{account}', [AccountController::class, 'update'])->middleware('permission:update_accounts')->name('accounts.update');
        Route::delete('accounts/{account}', [AccountController::class, 'destroy'])->middleware('permission:delete_accounts')->name('accounts.destroy');
        Route::post('accounts/cash-transfer', [AccountController::class, 'transferCash'])->middleware('permission:update_accounts')->name('accounts.cash-transfer');
        Route::post('accounts/pay', [AccountController::class, 'payCash'])->middleware('permission:update_accounts')->name('accounts.pay-cash');
        Route::post('accounts/update', [AccountController::class, 'updateCash'])->middleware('permission:update_accounts')->name('accounts.update-cash');
    });




    // Predefined Replies routes
    Route::middleware('permission:manage_predefined_replies')->group(function () {
        Route::get('predefined-replies', [PredefinedReplyController::class, 'index'])->name('predefinedReplies.index');
        Route::post('predefined-replies', [PredefinedReplyController::class, 'store'])->name('predefinedReplies.store');
        Route::get(
            'predefined-replies/{predefinedReply}/edit',
            [PredefinedReplyController::class, 'edit']
        )->name('predefinedReplies.edit');
        Route::put(
            'predefined-replies/{predefinedReply}',
            [PredefinedReplyController::class, 'update']
        )->name('predefinedReplies.update');
        Route::delete(
            'predefined-replies/{predefinedReply}',
            [PredefinedReplyController::class, 'destroy']
        )->name('predefinedReplies.destroy');
        Route::get(
            'predefined-replies/{predefinedReply}',
            [PredefinedReplyController::class, 'show']
        )->name('predefinedReplies.show');
    });
















    // Announcements routes
    Route::middleware('permission:manage_announcements')->group(function () {
        Route::get('announcements', [AnnouncementController::class, 'index'])->name('announcements.index');
        Route::post('announcements', [AnnouncementController::class, 'store'])->name('announcements.store');
        Route::get('announcements/{announcement}', [AnnouncementController::class, 'show'])->name('announcements.show');
        Route::get('announcements/{announcement}/edit', [AnnouncementController::class, 'edit'])->name('announcements.edit');
        Route::put('announcements/{announcement}', [AnnouncementController::class, 'update'])->name('announcements.update');
        Route::delete(
            'announcements/{announcement}',
            [AnnouncementController::class, 'destroy']
        )->name('announcements.destroy');
        Route::post(
            'announcements/{announcement}/active-deactive-client',
            [AnnouncementController::class, 'activeDeActiveClient']
        )->name('announcement.active.deactive.client');
        Route::get(
            'announcement-detail/{announcement}',
            [AnnouncementController::class, 'getAnnouncementDetails']
        )->name('announcement.details');
        Route::post(
            'announcements/{announcement}/change-status',
            [AnnouncementController::class, 'statusChange']
        )->name('announcements.status.change');
    });

    Route::get('all_notices', [NoticeController::class, 'all_notices'])->name('notices.all_notices');
    Route::get('notices', [NoticeController::class, 'index'])->name('notices.index');
    Route::get('notices/create', [NoticeController::class, 'create'])->name('notices.create');
    Route::post('notices', [NoticeController::class, 'store'])->name('notices.store');
    Route::get('notices/{notice}/edit', [NoticeController::class, 'edit'])->name('notices.edit');
    Route::put('notices/{notice}', [NoticeController::class, 'update'])->name('notices.update');
    Route::delete('notices/{notice}', [NoticeController::class, 'destroy'])->name('notices.destroy');
    // Calendar routes
    Route::middleware('permission:manage_calenders')->group(function () {
        Route::get('calendars', [CalendarController::class, 'index'])->name('calendars.index');
        Route::get('calendar-list', [CalendarController::class, 'calendarList']);
    });

    // Contracts type routes
    Route::middleware('permission:manage_contracts_types')->group(function () {
        Route::get('contract-types', [ContractTypeController::class, 'index'])->name('contract-types.index');
        Route::post('contract-types', [ContractTypeController::class, 'store'])->name('contract-types.store');
        Route::get(
            'contract-types/{contractType}/edit',
            [ContractTypeController::class, 'edit']
        )->name('contract-types.edit');
        Route::put('contract-types/{contractType}', [ContractTypeController::class, 'update'])->name('contract-types.update');
        Route::delete(
            'contract-types/{contractType}',
            [ContractTypeController::class, 'destroy']
        )->name('contract-types.destroy');
    });


    Route::middleware(['permission:view_users|create_users|update_users|delete_users'])->group(function () {
        Route::get('members', [MemberController::class, 'index'])->middleware('permission:view_users')->name('members.index');
        Route::post('members', [MemberController::class, 'store'])->middleware('permission:create_users')->name('members.store');
        Route::get('members/create', [MemberController::class, 'create'])->middleware('permission:create_users')->name('members.create');
        Route::get('members/{member}/edit', [MemberController::class, 'edit'])->middleware('permission:update_users')->name('members.edit');
        Route::put('members/{member}', [MemberController::class, 'update'])->middleware('permission:update_users')->name('members.update');
        Route::get('members/{member}', [MemberController::class, 'show'])->middleware('permission:view_users')->name('members.show');
        Route::get('members/{member}/{group}', [MemberController::class, 'show'])->middleware('permission:view_users');
        Route::delete('members/{member}', [MemberController::class, 'destroy'])->middleware('permission:delete_users')->name('members.destroy');
        Route::post('members/{member}/active-deactive-administrator', [
            MemberController::class,
            'activeDeActiveAdministrator'
        ])->middleware('permission:update_users')->name('members.active.deactive');
        Route::post('members/{member}/email-send', [MemberController::class, 'resendEmailVerification'])->middleware('permission:update_users')->name('email-send');
        Route::post('members/{member}/email-verify', [MemberController::class, 'emailVerified'])->middleware('permission:update_users')->name('email-verify');
    });


    // Leads routes
    Route::group(['middleware' => ['permission:view_leads|create_leads|update_leads|delete_leads']], function () {

        Route::get('leads', [LeadController::class, 'index'])->name('leads.index');
        Route::get('leads/create/{customerId?}', [LeadController::class, 'create'])->middleware('permission:create_leads')->name('leads.create');
        Route::post('leads', [LeadController::class, 'store'])->middleware('permission:create_leads')->name('leads.store');
        // Route::get('leads/{lead}', [LeadController::class, 'show'])->name('leads.show');
        Route::get('leads/{lead}', [LeadController::class, 'view'])->middleware('permission:view_leads')->name('leads.show');
        Route::get('leads/{lead}/edit', [LeadController::class, 'edit'])->middleware('permission:update_leads')->name('leads.edit');
        Route::put('leads/{lead}', [LeadController::class, 'update'])->middleware('permission:update_leads')->name('leads.update');
        Route::delete('leads/{lead}', [LeadController::class, 'destroy'])->middleware('permission:delete_leads')->name('leads.destroy');


        Route::put(
            'leads/{lead}/status/{status}',
            [LeadController::class, 'changeStatus']
        )->name('leads.changeStatus');
        Route::get('leads-kanban-list', [LeadController::class, 'kanbanList'])->name('leads.kanbanList');
        Route::post(
            'contact-as-per-customer',
            [LeadController::class, 'contactAsPerCustomer']
        )->name('leads.contactAsPerCustomer');
        Route::get('leads/{lead}/{group}', [LeadController::class, 'show']);
        Route::post(
            'leads/{lead}/{group}/notes-count',
            [LeadController::class, 'getNotesCount']
        );
        Route::post(
            'lead-convert-customer',
            [CustomerController::class, 'leadConvertToCustomer']
        )->name('lead.convert.customer');
        Route::get(
            'leads-convert-chart',
            [LeadController::class, 'leadConvertChart']
        )->name('leads.leadConvertChart');
    });


    Route::middleware('permission:manage_goals')->group(function () {
        Route::get('goals', [GoalController::class, 'index'])->name('goals.index');
        Route::post('goals', [GoalController::class, 'store'])->name('goals.store');
        Route::get('goals/create', [GoalController::class, 'create'])->name('goals.create');
        Route::put('goals/{goal}', [GoalController::class, 'update'])->name('goals.update');
        Route::get('goals/{goal}', [GoalController::class, 'show'])->name('goals.show');
        Route::delete('goals/{goal}', [GoalController::class, 'destroy'])->name('goals.destroy');
        Route::get('goals/{goal}/edit', [GoalController::class, 'edit'])->name('goals.edit');
    });

    // Contracts routes
    Route::middleware('permission:manage_contracts')->group(function () {
        Route::get('contracts', [ContractController::class, 'index'])->name('contracts.index');
        Route::post('contracts', [ContractController::class, 'store'])->name('contracts.store');
        Route::get('contracts/create/{customerId?}', [ContractController::class, 'create'])->name('contracts.create');
        Route::put('contracts/{contract}', [ContractController::class, 'update'])->name('contracts.update');
        Route::get('contracts/{contract}', [ContractController::class, 'show'])->name('contracts.show');
        Route::delete('contracts/{contract}', [ContractController::class, 'destroy'])->name('contracts.destroy');
        Route::get('contracts/{contract}/edit', [ContractController::class, 'edit'])->name('contracts.edit');
        Route::get('contracts/{contract}/{group}', [ContractController::class, 'show']);
        Route::get(
            'contracts-summary',
            [ContractController::class, 'contractSummary']
        )->name('contracts.contractSummary');
    });

    // Proposals routes
    Route::middleware('permission:manage_proposals')->group(function () {
        Route::get('proposals', [ProposalController::class, 'index'])->name('proposals.index');
        Route::post('proposals', [ProposalController::class, 'store'])->name('proposals.store');
        Route::get('proposals/create/{relatedTo?}', [ProposalController::class, 'create'])->name('proposals.create');
        Route::get('proposals/{proposal}/edit', [ProposalController::class, 'edit'])->name('proposals.edit');
        Route::post('proposals/{proposal}', [ProposalController::class, 'update'])->name('proposals.update');
        Route::delete('proposals/{proposal}', [ProposalController::class, 'destroy'])->name('proposals.destroy');
        Route::get('proposals/{proposal}', [ProposalController::class, 'show'])->name('proposals.show');
        Route::put(
            'proposals/{proposal}/change-status',
            [ProposalController::class, 'changeStatus']
        )->name('proposal.change-status');
        Route::get(
            'proposals/{proposal}/view-as-customer',
            [ProposalController::class, 'viewAsCustomer']
        )->name('proposal.view-as-customer');
        Route::get('proposals/{proposal}/pdf', [ProposalController::class, 'convertToPdf'])->name('proposal.pdf');
        Route::post(
            'proposals/{proposal}/convert-to-invoice',
            [ProposalController::class, 'convertToInvoice']
        )->name('proposal.convert-to-invoice');
        Route::post(
            'proposals/{proposal}/convert-to-estimate',
            [ProposalController::class, 'convertToEstimate']
        )->name('proposal.convert-to-estimate');
        Route::get('proposals/{proposal}/{group}', [ProposalController::class, 'show']);
    });

    // Credit Notes routes
    Route::middleware(['permission:view_credit_notes|create_credit_notes|update_credit_notes|delete_credit_notes|export_credit_notes'])->group(function () {
        Route::get('credit-notes', [CreditNoteController::class, 'index'])->name('credit-notes.index');
        Route::get('credit-notes/invoice', [CreditNoteController::class, 'getInvoice'])->name('credit-notes.invoice');
        Route::post('credit-notes', [CreditNoteController::class, 'store'])->middleware('permission:create_credit_notes')->name('credit-notes.store');
        Route::get('credit-notes/create/{customerId?}', [CreditNoteController::class, 'create'])->middleware('permission:create_credit_notes')->name('credit-notes.create');
        Route::get('credit-notes/{creditNote}/edit', [CreditNoteController::class, 'edit'])->middleware('permission:update_credit_notes')->name('credit-notes.edit');
        Route::post('credit-notes/{creditNote}', [CreditNoteController::class, 'update'])->middleware('permission:update_credit_notes')->name('credit-notes.update');
        Route::delete('credit-notes/{creditNote}', [CreditNoteController::class, 'destroy'])->middleware('permission:delete_credit_notes')->name('credit-notes.destroy');
        Route::get('credit-notes/{creditNote}', [CreditNoteController::class, 'show'])->middleware('permission:view_credit_notes')->name('credit-notes.show');
        Route::put('credit-notes/{creditNote}/change-payment-status', [CreditNoteController::class, 'changePaymentStatus'])->middleware('permission:update_credit_notes')->name('credit-note.change-payment-status');
        Route::get('credit-notes/{creditNote}/view-as-customer', [CreditNoteController::class, 'viewAsCustomer'])->middleware('permission:view_credit_notes')->name('credit-note.view-as-customer');
        Route::get('credit-notes/{creditNote}/pdf', [CreditNoteController::class, 'convertToPdf'])->middleware('permission:export_credit_notes')->name('credit-note.pdf');
    });


    // setting routes
    Route::middleware('permission:manage_settings')->group(function () {
        Route::get('settings', [SettingController::class, 'show'])->name('settings.show');
        Route::post('settings', [SettingController::class, 'update'])->name('settings.update');
    });

    // Activity Log
    Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity.logs.index');
    Route::post('change-filter', [ActivityLogController::class, 'index'])->name('change.filter');

    Route::get(
        'translation-manager',
        [TranslationManagerController::class, 'index']
    )->name('translation-manager.index');
    Route::get(
        'translation-manager/{language}/edit',
        [TranslationManagerController::class, 'edit']
    )->name('translation.manager.edit');
    Route::get(
        'language/translation/{language}',
        [TranslationManagerController::class, 'showTranslation']
    )->name('language.translation');
    Route::post(
        'translation-manager',
        [TranslationManagerController::class, 'store']
    )->name('translation-manager.store');
    Route::put(
        'translation-manager/{language}',
        [TranslationManagerController::class, 'update']
    )->name('translation-manager.update');
    Route::delete(
        'translation-manager/{language}',
        [TranslationManagerController::class, 'destroy']
    )->name('translation.manager.destroy');
    Route::post(
        'language/translation/{language}/update',
        [TranslationManagerController::class, 'updateTranslation']
    )->name('language.translation.update');

    // Task routes
    Route::middleware('permission:manage_tasks')->group(function () {
        Route::get('tasks', [TaskController::class, 'index'])->name('tasks.index');
        Route::get(
            'tasks/create/{relatedTo?}/{customerId?}',
            [TaskController::class, 'create']
        )->name('tasks.create');
        Route::post('tasks', [TaskController::class, 'store'])->name('tasks.store');
        Route::get('tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');
        Route::get('tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
        Route::put('tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
        Route::delete('tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
        Route::get('change-owner', [TaskController::class, 'changeOwner'])->name('change-owner');
        Route::put('tasks/{task}/status/{status}', [TaskController::class, 'changeStatus'])->name('tasks.changeStatus');
        Route::get('tasks-kanban-list', [TaskController::class, 'kanbanList'])->name('tasks.kanbanList');
        Route::get(
            'tasks/{task}/comments-count',
            [TaskController::class, 'getCommentsCount']
        )->name('task.comments-count');
        Route::get('tasks/{task}/{group}', [TaskController::class, 'show']);
    });




    // Tickets routes
    Route::middleware('permission:manage_tickets')->group(function () {
        Route::get('tickets', [TicketController::class, 'index'])->name('ticket.index');
        Route::get('tickets/create', [TicketController::class, 'create'])->name('ticket.create');
        Route::post('tickets', [TicketController::class, 'store'])->name('ticket.store');
        Route::get('tickets/{ticket}', [TicketController::class, 'show'])->name('ticket.show');
        Route::get('tickets/{ticket}/edit', [TicketController::class, 'edit'])->name('ticket.edit');
        Route::put('tickets/{ticket}', [TicketController::class, 'update'])->name('ticket.update');
        Route::delete('tickets/{ticket}', [TicketController::class, 'destroy'])->name('ticket.destroy');
        Route::get(
            'tickets/predefinedReplyBody/{predefinedReplyId?}',
            [TicketController::class, 'getPredefinedReplyBody']
        )->name('ticket.reply.body');
        Route::get('tickets-attachment-download/{ticket}', [TicketController::class, 'downloadMedia']);
        Route::get('tickets/{ticket}/{group}', [TicketController::class, 'show'])->name('tickets.show');
        Route::post(
            'tickets/{ticket}/{group}/notes-count',
            [TicketController::class, 'getNotesCount']
        );
        Route::get('tickets-kanban-list', [TicketController::class, 'kanbanList'])->name('tickets.kanbanList');
        Route::put(
            'tickets/{ticket}/status/{statusId}',
            [TicketController::class, 'changeStatus']
        )->name('tickets.changeStatus');
        Route::delete(
            'ticket-attachment-delete',
            [TicketController::class, 'attachmentDelete']
        )->name('ticket.attachment');
        Route::get(
            'download-media/{mediaItem}',
            [TicketController::class, 'download']
        )->name('ticket.download.media');
    });

    // Ticket Priorities routes
    Route::middleware('permission:manage_ticket_priority')->group(function () {
        Route::get('ticket-priorities', [TicketPriorityController::class, 'index'])->name('ticketPriorities.index');
        Route::post('ticket-priorities', [TicketPriorityController::class, 'store'])->name('ticketPriorities.store');
        Route::get(
            'ticket-priorities/{ticketPriority}/edit',
            [TicketPriorityController::class, 'edit']
        )->name('ticketPriorities.edit');
        Route::put(
            'ticket-priorities/{ticketPriority}',
            [TicketPriorityController::class, 'update']
        )->name('ticketPriorities.update');
        Route::delete(
            'ticket-priorities/{ticketPriority}',
            [TicketPriorityController::class, 'destroy']
        )->name('ticketPriorities.destroy');
        Route::post(
            'ticket-priorities/{ticket_priority_id}/active-deactive',
            [TicketPriorityController::class, 'activeDeActiveCategory']
        )->name('active.deactive');
    });

    // Ticket Status routes
    Route::middleware('permission:manage_ticket_statuses')->group(function () {
        Route::get('ticket-statuses', [TicketStatusController::class, 'index'])->name('ticket.status.index');
        Route::post('ticket-statuses', [TicketStatusController::class, 'store'])->name('ticket.status.store');
        Route::get(
            'ticket-statuses/{ticketStatus}/edit',
            [TicketStatusController::class, 'edit']
        )->name('ticket.status.edit');
        Route::put(
            'ticket-statuses/{ticketStatus}',
            [TicketStatusController::class, 'update']
        )->name('ticket.status.update');
        Route::delete(
            'ticket-statuses/{ticketStatus}',
            [TicketStatusController::class, 'destroy']
        )->name('ticket.status.destroy');
    });

    // Payment Modes routes
    Route::middleware('permission:manage_payment_mode')->group(function () {
        Route::get('payment-modes', [PaymentModeController::class, 'index'])->name('payment-modes.index');
        Route::post('payment-modes', [PaymentModeController::class, 'store'])->name('payment-modes.store');
        Route::get('payment-modes/{paymentMode}/edit', [PaymentModeController::class, 'edit'])->name('payment-modes.edit');
        Route::put('payment-modes/{paymentMode}', [PaymentModeController::class, 'update'])->name('payment-modes.update');
        Route::delete(
            'payment-modes/{paymentMode}',
            [PaymentModeController::class, 'destroy']
        )->name('payment-modes.destroy');
        Route::post(
            'payment-modes/{paymentMode}/active-deactive',
            [PaymentModeController::class, 'activeDeActivePaymentMode']
        )->name('payment-modes.active.deactive');
        Route::get(
            'payment-modes/{paymentMode}',
            [PaymentModeController::class, 'show']
        )->name('payment-modes.show');
    });

    // Lead Sources route
    Route::middleware('permission:manage_lead_sources')->group(function () {
        Route::get('lead-sources', [LeadSourceController::class, 'index'])->name('lead.source.index');
        Route::post('lead-sources', [LeadSourceController::class, 'store'])->name('lead.source.store');
        Route::get('lead-sources/{leadSource}/edit', [LeadSourceController::class, 'edit'])->name('lead.source.edit');
        Route::put('lead-sources/{leadSource}', [LeadSourceController::class, 'update'])->name('lead.source.update');
        Route::delete('lead-sources/{leadSource}', [LeadSourceController::class, 'destroy'])->name('lead.source.destroy');
    });

    // Lead Status routes
    Route::middleware('permission:manage_lead_status')->group(function () {
        Route::get('lead-status', [LeadStatusController::class, 'index'])->name('lead.status.index');
        Route::post('lead-status', [LeadStatusController::class, 'store'])->name('lead.status.store');
        Route::get('lead-status/{leadStatus}/edit', [LeadStatusController::class, 'edit'])->name('lead.status.edit');
        Route::put('lead-status/{leadStatus}', [LeadStatusController::class, 'update'])->name('lead.status.update');
        Route::delete('lead-status/{leadStatus}', [LeadStatusController::class, 'destroy'])->name('lead.status.destroy');
    });





    Route::get('customer-address', [InvoiceController::class, 'getCustomerAddress'])->name('get.customer.address');
    Route::get(
        'credit-note-customer-address',
        [CreditNoteController::class, 'getCustomerAddress']
    )->name('get.creditnote.customer.address');
    Route::get(
        'estimates-customer-address',
        [EstimateController::class, 'getCustomerAddress']
    )->name('get.estimate.customer.address');
    Route::get(
        'proposal-customer-address',
        [ProposalController::class, 'getCustomerAddress']
    )->name('get.proposal.customer.address');

    // Payments routes
    Route::middleware('permission:manage_payment_mode')->group(function () {
        Route::get('payments', [PaymentController::class, 'index'])->name('payments.index');
        Route::post('payments', [PaymentController::class, 'store'])->name('payments.store');
        Route::delete('payments/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy');
        Route::get('payments/edit', [PaymentController::class, 'addPayment'])->name('payments.create');
    });

    // Payment for Invoices routes
    Route::get('payments-list', [Listing\PaymentListing::class, 'index'])->name('payments.list.index');
    Route::get('payment-details/{payment?}', [Listing\PaymentListing::class, 'show'])->name('payments.list.show');
    Route::get('payments-list/create', [Listing\PaymentListing::class, 'create'])->name('payments.list.create');
    Route::post('payments-list/store', [Listing\PaymentListing::class, 'store'])->name('payments.list.store');
    Route::put('payments-list/{payment}', [Listing\PaymentListing::class, 'update'])->name('payments.list.update');
    Route::get('payments-list/{payment}/edit', [Listing\PaymentListing::class, 'edit'])->name('payments.list.edit');
    Route::get('payments-list/{payment}/show', [Listing\PaymentListing::class, 'show'])->name('payments.list.show');
    Route::delete('payments-list/{payment}', [Listing\PaymentListing::class, 'destroy'])->name('payments.list.destroy');
    Route::get('payments-list/export-csv', [Listing\PaymentListing::class, 'exportCsv'])->name('payments.list.exportCsv');









    // Profile routes
    Route::post('change-password', [UserController::class, 'changePassword'])->name('change.password');
    Route::get('profile', [UserController::class, 'editProfile'])->name('profile');
    Route::post('update-profile', [UserController::class, 'updateProfile'])->name('update.profile');
    Route::post('change-language', [UserController::class, 'changeLanguage'])->name('change.language');

    Route::post('contract-month-filter', [DashboardController::class, 'contractMonthFilter'])->name('contract.month.filter');




    Route::group(['middleware' => ['permission:view_currencies|create_currencies|update_currencies|delete_currencies']], function () {
        Route::get('currencies', [CurrencyController::class, 'index'])->name('currencies.index');
        Route::get('currencies/create', [CurrencyController::class, 'create'])->middleware('permission:create_currencies')->name('currencies.create');
        Route::post('currencies', [CurrencyController::class, 'store'])->middleware('permission:create_currencies')->name('currencies.store');
        Route::get('currencies/{currency}/view', [CurrencyController::class, 'view'])->middleware('permission:view_currencies')->name('currencies.view');
        Route::get('currencies/{currency}/edit', [CurrencyController::class, 'edit'])->middleware('permission:update_currencies')->name('currencies.edit');
        Route::put('currencies/{currency}', [CurrencyController::class, 'update'])->middleware('permission:update_currencies')->name('currencies.update');
        Route::delete('currencies/{currency}', [CurrencyController::class, 'destroy'])->middleware('permission:delete_currencies')->name('currencies.destroy');
    });



    // Country module routes
    Route::get('countries', [CountryController::class, 'index'])->name('countries.index');
    Route::post('countries', [CountryController::class, 'store'])->name('countries.store');
    Route::get('countries/{country}/edit', [CountryController::class, 'edit'])->name('countries.edit');
    Route::put('countries/{country}', [CountryController::class, 'update'])->name('countries.update');
    Route::delete('countries/{country}', [CountryController::class, 'destroy'])->name('countries.destroy');
    Route::get('countries/{country}', [CountryController::class, 'show'])->name('countries.show');





    // states routes
    Route::group(['middleware' => ['permission:view_states|create_states|update_states|delete_states']], function () {
        Route::get('states', [StateController::class, 'index'])->name('states.index');
        Route::get('states/create', [StateController::class, 'create'])->middleware('permission:create_states')->name('states.create');
        Route::post('states', [StateController::class, 'store'])->middleware('permission:create_states')->name('states.store');
        Route::get('states/{state}/view', [StateController::class, 'view'])->middleware('permission:view_states')->name('states.view');
        Route::get('states/{state}/edit', [StateController::class, 'edit'])->middleware('permission:update_states')->name('states.edit');
        Route::put('states/{state}', [StateController::class, 'update'])->middleware('permission:update_states')->name('states.update');
        Route::delete('states/{state}', [StateController::class, 'destroy'])->middleware('permission:delete_states')->name('states.destroy');
    });

    // cities routes
    Route::group(['middleware' => ['permission:view_cities|create_cities|update_cities|delete_cities']], function () {
        Route::get('cities', [CityController::class, 'index'])->name('cities.index');
        Route::get('cities/create', [CityController::class, 'create'])->middleware('permission:create_cities')->name('cities.create');
        Route::post('cities', [CityController::class, 'store'])->middleware('permission:create_cities')->name('cities.store');
        Route::get('cities/{city}/view', [CityController::class, 'view'])->middleware('permission:view_cities')->name('cities.view');
        Route::get('cities/{city}/edit', [CityController::class, 'edit'])->middleware('permission:update_cities')->name('cities.edit');
        Route::put('cities/{city}', [CityController::class, 'update'])->middleware('permission:update_cities')->name('cities.update');
        Route::delete('cities/{city}', [CityController::class, 'destroy'])->middleware('permission:delete_cities')->name('cities.destroy');
    });

    // areas routes
    Route::group(['middleware' => ['permission:view_areas|create_areas|update_areas|delete_areas']], function () {
        Route::get('areas', [AreaController::class, 'index'])->name('areas.index');
        Route::get('areas/create', [AreaController::class, 'create'])->middleware('permission:create_areas')->name('areas.create');
        Route::post('areas', [AreaController::class, 'store'])->middleware('permission:create_areas')->name('areas.store');
        Route::get('areas/{area}/view', [AreaController::class, 'view'])->middleware('permission:view_areas')->name('areas.view');
        Route::get('areas/{area}/edit', [AreaController::class, 'edit'])->middleware('permission:update_areas')->name('areas.edit');
        Route::put('areas/{area}', [AreaController::class, 'update'])->middleware('permission:update_areas')->name('areas.update');
        Route::delete('areas/{area}', [AreaController::class, 'destroy'])->middleware('permission:delete_areas')->name('areas.destroy');
    });




});

Route::middleware(['auth', 'xss', 'checkUserStatus', 'checkRoleAdmin', 'role:client'])->prefix('client')->group(function () {
    Route::get('dashboard', [Clients\DashboardController::class, 'index'])->name('clients.dashboard');


    // Projects routes
    Route::middleware('permission:contact_projects')->group(function () {
        Route::get('projects', [Clients\ProjectController::class, 'index'])->name('clients.projects.index');
        Route::get('projects/{project}', [Clients\ProjectController::class, 'show'])->name('clients.projects.show');
        Route::get(
            'projects/{project}/{group}',
            [Clients\ProjectController::class, 'show']
        );
    });

    // Tasks routes
    Route::get('tasks', [Clients\TaskController::class, 'index'])->name('clients.tasks.index');
    Route::get('tasks/{task}', [Clients\TaskController::class, 'show'])->name('clients.tasks.show');
    Route::get('tasks/{task}/{group}', [Clients\TaskController::class, 'show']);

    // Reminder routes
    Route::get('reminder', [Clients\ReminderController::class, 'index'])->name('clients.reminder.index');

    // Invoices routes
    Route::middleware('permission:contact_invoices')->group(function () {
        Route::get('invoices', [Clients\InvoiceController::class, 'index'])->name('clients.invoices.index');
        Route::get(
            'invoices/{invoice}/view-as-customer',
            [Clients\InvoiceController::class, 'viewAsCustomer']
        )->name('clients.invoices.view-as-customer');
        Route::get(
            'invoices/{invoice}/pdf',
            [Clients\InvoiceController::class, 'covertToPdf']
        )->name('clients.invoice.pdf');
        Route::post('invoice-stripe-payment', [PaymentController::class, 'createSession']);
        Route::get(
            'invoice-payment-success',
            [PaymentController::class, 'paymentSuccess']
        )->name('clients.invoice-payment-success');
        Route::get(
            'invoice-failed-payment',
            [PaymentController::class, 'handleFailedPayment']
        )->name('clients.invoice-failed-payment');
    });

    // Proposals routes
    Route::middleware('permission:contact_proposals')->group(function () {
        Route::get('proposals', [Clients\ProposalController::class, 'index'])->name('clients.proposals.index');
        Route::get(
            'proposals/{proposal}/view-as-customer',
            [Clients\ProposalController::class, 'viewAsCustomer']
        )->name('clients.proposals.view-as-customer');
        Route::post(
            'proposals/{proposal}/change-status',
            [Clients\ProposalController::class, 'changeStatus']
        )->name('clients.proposals.change-status');
        Route::get('proposals/{proposal}/pdf', [Clients\ProposalController::class, 'covertToPdf'])->name('clients.proposal.pdf');
    });

    // Contracts routes
    Route::middleware('permission:contact_contracts')->group(function () {
        Route::get('contracts', [Clients\ContractController::class, 'index'])->name('clients.contracts.index');
        Route::get('contracts/{contract}/view-as-customer', [Clients\ContractController::class, 'viewAsCustomer'])
            ->name('clients.contracts.view-as-customer');
        Route::get(
            'contracts/{contract}/pdf',
            [Clients\ContractController::class, 'convertToPdf']
        )->name('clients.contracts.pdf');
        Route::get(
            'contracts-summary',
            [Clients\ContractController::class, 'contractSummary']
        )->name('contracts.contract-summary');
    });

    // Estimates routes
    Route::middleware('permission:contact_estimates')->group(function () {
        Route::get('estimates', [Clients\EstimateController::class, 'index'])->name('clients.estimates.index');
        Route::get('estimates/{estimate}/view-as-customer', [Clients\EstimateController::class, 'viewAsCustomer'])
            ->name('clients.estimates.view-as-customer');
        Route::get('estimates/{estimate}/pdf', [Clients\EstimateController::class, 'convertToPDF'])->name('clients.estimate.pdf');
        Route::post('estimates/{estimate}/change-status', [Clients\EstimateController::class, 'changeStatus'])
            ->name('clients.estimates.change-status');
    });

    // Announcements routes
    Route::get('announcements', [Clients\AnnouncementController::class, 'index'])->name('clients.announcements.index');
    Route::get(
        'announcements/{announcement}',
        [Clients\AnnouncementController::class, 'show']
    )->name('clients.announcements.show');

    // Company Details Routes
    Route::get(
        'company-details',
        [Clients\CompanyController::class, 'companyDetails']
    )->name('clients.company-details');
    Route::put('company-details/{customer}', [Clients\CompanyController::class, 'update'])->name('clients.update');

    // Profile routes
    Route::post('change-password', [Clients\UserController::class, 'changePassword'])->name('clients.change.password');
    Route::get('profile', [Clients\UserController::class, 'editProfile'])->name('clients.profile');
    Route::post('update-profile', [Clients\UserController::class, 'updateProfile'])->name('clients.update.profile');
    Route::post('change-language', [Clients\UserController::class, 'changeLanguage'])->name('clients.change.language');

    //Header Client Notification
    Route::get(
        'get-notifications',
        [Clients\DashboardController::class, 'getNotifications']
    )->name('client.notifications.index');
    Route::post(
        'notification/{notification}/read',
        [Clients\DashboardController::class, 'readNotification']
    )->name('client.notifications.read');
    Route::post(
        'read-all-notification',
        [Clients\DashboardController::class, 'readAllNotification']
    )->name('client.notifications.read.all');

    // Ticket routes
    Route::get('tickets', [Clients\TicketController::class, 'index'])->name('client.tickets.index');
    Route::get('tickets/create', [Clients\TicketController::class, 'create'])->name('client.tickets.create');
    Route::post('tickets', [Clients\TicketController::class, 'store'])->name('client.tickets.store');
    Route::get('tickets/{ticket}', [Clients\TicketController::class, 'show'])->name('client.tickets.show');
    Route::delete('tickets/{ticket}', [Clients\TicketController::class, 'destroy'])->name('client.tickets.destroy');
});

Route::middleware(['auth', 'xss', 'checkUserStatus'])->group(function () {
    // ticket reply routes
    Route::post('ticket-reply', [TicketReplyController::class, 'store'])->name('ticket.reply.store');
    Route::get('ticket-reply/{ticket}/edit', [TicketReplyController::class, 'edit'])->name('ticket.reply.edit');
    Route::put('ticket-reply/{ticket}', [TicketReplyController::class, 'update'])->name('ticket.reply.update');
    Route::delete('ticket-reply/{ticket}', [TicketReplyController::class, 'destroy'])->name('ticket.reply.destroy');
});


// Route::group(['middleware' => ['permission:view_beds|create_beds|update_beds|delete_beds']], function () {
//     Route::get('beds', [BedController::class, 'index'])->name('beds.index');
//     Route::get('beds/create', [BedController::class, 'create'])->middleware('permission:create_beds')->name('beds.create');
//     Route::post('beds', [BedController::class, 'store'])->middleware('permission:create_beds')->name('beds.store');
//     Route::get('beds/{bed}/view', [BedController::class, 'view'])->middleware('permission:view_beds')->name('beds.view');
//     Route::get('beds/{bed}/edit', [BedController::class, 'edit'])->middleware('permission:update_beds')->name('beds.edit');
//     Route::put('beds/{bed}', [BedController::class, 'update'])->middleware('permission:update_beds')->name('beds.update');
//     Route::delete('beds/{bed}', [BedController::class, 'destroy'])->middleware('permission:delete_beds')->name('beds.destroy');
// });

Route::group([], function () {
    Route::get('beds', [BedController::class, 'index'])->name('beds.index');
    Route::get('beds/create', [BedController::class, 'create'])->name('beds.create');
    Route::post('beds', [BedController::class, 'store'])->name('beds.store');
    Route::get('beds/{bed}/view', [BedController::class, 'view'])->name('beds.view');
    Route::get('beds/{bed}/edit', [BedController::class, 'edit'])->name('beds.edit');
    Route::put('beds/{bed}', [BedController::class, 'update'])->name('beds.update');
    Route::delete('beds/{bed}', [BedController::class, 'destroy'])->name('beds.destroy');
    Route::get('beds/export/{format}', [BedController::class, 'export'])->name('beds.export');
});
Route::group([], function () {
    Route::get('wake-up-calls', [WakeUpCallController::class, 'index'])->name('wake_up_calls.index');
    Route::get('wake-up-calls/create', [WakeUpCallController::class, 'create'])->name('wake_up_calls.create');
    Route::post('wake-up-calls', [WakeUpCallController::class, 'store'])->name('wake_up_calls.store');
    Route::get('wake-up-calls/{wakeUpCall}/view', [WakeUpCallController::class, 'view'])->name('wake_up_calls.view');
    Route::get('wake-up-calls/{wakeUpCall}/edit', [WakeUpCallController::class, 'edit'])->name('wake_up_calls.edit');
    Route::put('wake-up-calls/{wakeUpCall}', [WakeUpCallController::class, 'update'])->name('wake_up_calls.update');
    Route::delete('wake-up-calls/{wakeUpCall}', [WakeUpCallController::class, 'destroy'])->name('wake_up_calls.destroy');
    Route::get('wake-up-calls/export/{format}', [WakeUpCallController::class, 'export'])->name('wakeUpCalls.export');
});

Route::group([], function () {
    Route::get('booking-lists', [BookingListController::class, 'index'])->name('booking_lists.index');
    Route::get('booking-lists/create', [BookingListController::class, 'create'])->name('booking_lists.create');
    Route::post('booking-lists', [BookingListController::class, 'store'])->name('booking_lists.store');
    Route::get('booking-lists/{bookingList}/view', [BookingListController::class, 'view'])->name('booking_lists.view');
    Route::get('booking-lists/{bookingList}/edit', [BookingListController::class, 'edit'])->name('booking_lists.edit');
    Route::put('booking-lists/{bookingList}', [BookingListController::class, 'update'])->name('booking_lists.update');
    Route::delete('booking-lists/{bookingList}', [BookingListController::class, 'destroy'])->name('booking_lists.destroy');
    Route::get('booking-lists/export/{format}', [BookingListController::class, 'export'])->name('booking_lists.export');
});

Route::group([], function () {
    Route::get('check-ins', [CheckInController::class, 'index'])->name('check_ins.index');
    Route::get('check-ins/create', [CheckInController::class, 'create'])->name('check_ins.create');
    Route::post('check-ins', [CheckInController::class, 'store'])->name('check_ins.store');
    Route::get('check-ins/{checkIn}/view', [CheckInController::class, 'view'])->name('check_ins.view');
    Route::get('check-ins/{checkIn}/edit', [CheckInController::class, 'edit'])->name('check_ins.edit');
    Route::put('check-ins/{checkIn}', [CheckInController::class, 'update'])->name('check_ins.update');
    Route::delete('check-ins/{checkIn}', [CheckInController::class, 'destroy'])->name('check_ins.destroy');
    Route::get('check-ins/export/{format}', [CheckInController::class, 'export'])->name('check_ins.export');
});


Route::group([], function () {
    Route::get('check-outs', [CheckOutController::class, 'index'])->name('check_outs.index');

});


Route::group([], function () {
    Route::get('complementaries', [ComplementaryController::class, 'index'])->name('complementaries.index');
    Route::get('complementaries/create', [ComplementaryController::class, 'create'])->name('complementaries.create');
    Route::post('complementaries', [ComplementaryController::class, 'store'])->name('complementaries.store');
    Route::get('complementaries/{complementary}/view', [ComplementaryController::class, 'view'])->name('complementaries.view');
    Route::get('complementaries/{complementary}/edit', [ComplementaryController::class, 'edit'])->name('complementaries.edit');
    Route::put('complementaries/{complementary}', [ComplementaryController::class, 'update'])->name('complementaries.update');
    Route::delete('complementaries/{complementary}', [ComplementaryController::class, 'destroy'])->name('complementaries.destroy');
    Route::get('complementaries/export/{format}', [ComplementaryController::class, 'export'])->name('complementaries.export');
});

Route::group([], function () {
    Route::get('booking-sources', [BookingSourceController::class, 'index'])->name('booking-sources.index');
    Route::get('booking-sources/create', [BookingSourceController::class, 'create'])->name('booking-sources.create');
    Route::post('booking-sources', [BookingSourceController::class, 'store'])->name('booking-sources.store');
    Route::get('booking-sources/{bookingSource}/view', [BookingSourceController::class, 'view'])->name('booking-sources.view');
    Route::get('booking-sources/{bookingSource}/edit', [BookingSourceController::class, 'edit'])->name('booking-sources.edit');
    Route::put('booking-sources/{bookingSource}', [BookingSourceController::class, 'update'])->name('booking-sources.update');
    Route::delete('booking-sources/{bookingSource}', [BookingSourceController::class, 'destroy'])->name('booking-sources.destroy');
    Route::get('booking-sources/export/{format}', [BookingSourceController::class, 'export'])->name('booking-sources.export');
});

Route::group([], function () {
    Route::get('award-lists', [AwardListController::class, 'index'])->name('award-lists.index');
    Route::get('award-lists/create', [AwardListController::class, 'create'])->name('award-lists.create');
    Route::post('award-lists', [AwardListController::class, 'store'])->name('award-lists.store');
    Route::get('award-lists/{awardList}/view', [AwardListController::class, 'view'])->name('award-lists.view');
    Route::get('award-lists/{awardList}/edit', [AwardListController::class, 'edit'])->name('award-lists.edit');
    Route::put('award-lists/{awardList}', [AwardListController::class, 'update'])->name('award-lists.update');
    Route::delete('award-lists/{awardList}', [AwardListController::class, 'destroy'])->name('award-lists.destroy');
    Route::get('award-lists/export/{format}', [AwardListController::class, 'export'])->name('award-lists.export');
});

Route::group([], function () {
    Route::get('notice-boards', [NoticeBoardController::class, 'index'])->name('notice-boards.index');
    Route::get('notice-boards/create', [NoticeBoardController::class, 'create'])->name('notice-boards.create');
    Route::post('notice-boards', [NoticeBoardController::class, 'store'])->name('notice-boards.store');
    Route::get('notice-boards/{noticeBoard}/view', [NoticeBoardController::class, 'view'])->name('notice-boards.view');
    Route::get('notice-boards/{noticeBoard}/edit', [NoticeBoardController::class, 'edit'])->name('notice-boards.edit');
    Route::put('notice-boards/{noticeBoard}', [NoticeBoardController::class, 'update'])->name('notice-boards.update');
    Route::delete('notice-boards/{noticeBoard}', [NoticeBoardController::class, 'destroy'])->name('notice-boards.destroy');
    Route::get('notice-boards/export/{format}', [NoticeBoardController::class, 'export'])->name('notice-boards.export');
});

Route::group([], function () {
    Route::get('positions', [PositionController::class, 'index'])->name('positions.index');
    Route::get('positions/create', [PositionController::class, 'create'])->name('positions.create');
    Route::post('positions', [PositionController::class, 'store'])->name('positions.store');
    Route::get('positions/{position}/view', [PositionController::class, 'view'])->name('positions.view');
    Route::get('positions/{position}/edit', [PositionController::class, 'edit'])->name('positions.edit');
    Route::put('positions/{position}', [PositionController::class, 'update'])->name('positions.update');
    Route::delete('positions/{position}', [PositionController::class, 'destroy'])->name('positions.destroy');
    Route::get('positions/export/{format}', [PositionController::class, 'export'])->name('positions.export');
});
Route::group([], function () {
    Route::get('job-categories', [JobCategoryController::class, 'index'])->name('job-categories.index');
    Route::get('job-categories/create', [JobCategoryController::class, 'create'])->name('job-categories.create');
    Route::post('job-categories', [JobCategoryController::class, 'store'])->name('job-categories.store');
    Route::get('job-categories/{jobCategory}/view', [JobCategoryController::class, 'view'])->name('job-categories.view');
    Route::get('job-categories/{jobCategory}/edit', [JobCategoryController::class, 'edit'])->name('job-categories.edit');
    Route::put('job-categories/{jobCategory}', [JobCategoryController::class, 'update'])->name('job-categories.update');
    Route::delete('job-categories/{jobCategory}', [JobCategoryController::class, 'destroy'])->name('job-categories.destroy');
    Route::get('job-categories/export/{format}', [JobCategoryController::class, 'export'])->name('job-categories.export');
    Route::put('job-categories/{job_category}/status', [JobCategoryController::class, 'status'])->name('job-categories.status');
});

Route::group([], function () {
    // Shift Routes
    Route::get('shifts', [ShiftController::class, 'index'])->name('shifts.index');
    Route::get('shifts/create', [ShiftController::class, 'create'])->name('shifts.create');
    Route::post('shifts', [ShiftController::class, 'store'])->name('shifts.store');
    Route::get('shifts/{shift}', [ShiftController::class, 'view'])->name('shifts.view');
    Route::get('shifts/{shift}/edit', [ShiftController::class, 'edit'])->name('shifts.edit');
    Route::put('shifts/{shift}', [ShiftController::class, 'update'])->name('shifts.update');
    Route::delete('shifts/{shift}', [ShiftController::class, 'destroy'])->name('shifts.destroy');
    Route::get('shifts/export/{format}', [ShiftController::class, 'export'])->name('shifts.export');
});

Route::group([], function () {
    // Job Post Routes
    Route::get('job-posts', [JobPostController::class, 'index'])->name('job-posts.index');
    Route::get('job-posts/create', [JobPostController::class, 'create'])->name('job-posts.create');
    Route::post('job-posts', [JobPostController::class, 'store'])->name('job-posts.store');
    Route::get('job-posts/{jobPost}', [JobPostController::class, 'view'])->name('job-posts.view');
    Route::get('job-posts/{jobPost}/edit', [JobPostController::class, 'edit'])->name('job-posts.edit');
    Route::put('job-posts/{jobPost}', [JobPostController::class, 'update'])->name('job-posts.update');
    Route::delete('job-posts/{jobPost}', [JobPostController::class, 'destroy'])->name('job-posts.destroy');
    Route::get('job-posts/export/{format}', [JobPostController::class, 'export'])->name('job-posts.export');
    // Route::post('job-posts/{jobPost}/toggle-status', [JobPostController::class, 'toggleStatus'])->name('job-posts.toggle-status');
});
Route::get('article-search', function () {
    return view('articles.search');
});

require __DIR__ . '/upgrade.php';
