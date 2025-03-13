<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\Auth\ForgotPasswordController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\ResetPasswordController;
use App\Http\Controllers\Admin\AutomaticGatewayController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DepositController;
use App\Http\Controllers\Admin\ExtensionController;
use App\Http\Controllers\Admin\FileTypeController;
use App\Http\Controllers\Admin\FrontendController;
use App\Http\Controllers\Admin\GeneralSettingController;
use App\Http\Controllers\Admin\JobController;
use App\Http\Controllers\Admin\KycController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\ManageUsersController;
use App\Http\Controllers\Admin\ManualGatewayController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\PageBuilderController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\Admin\SupportTicketController;
use App\Http\Controllers\Admin\SystemController;
use App\Http\Controllers\Admin\WithdrawalController;
use App\Http\Controllers\Admin\WithdrawMethodController;
use Illuminate\Support\Facades\Route;

Route::middleware('admin.guest')->group(function () {

    Route::controller(LoginController::class)->group(function () {
        Route::get('/', 'showLoginForm')->name('login');
        Route::post('/', 'login')->name('login');
        Route::get('logout', 'logout')
            ->middleware('admin')
            ->withoutMiddleware('admin.guest')
            ->name('logout');
    });

    // Admin Password Reset
    Route::prefix('password')->name('password.')->group(function () {
        Route::controller(ForgotPasswordController::class)->group(function () {
            Route::get('reset', 'showLinkRequestForm')->name('reset');
            Route::post('reset', 'sendResetCodeEmail');
            Route::get('code-verify', 'codeVerify')->name('code.verify');
            Route::post('verify-code', 'verifyCode')->name('verify.code');
        });

        Route::controller(ResetPasswordController::class)->group(function () {
            Route::get('reset/{token}', 'showResetForm')->name('reset.form');
            Route::post('reset/change', 'reset')->name('change');
        });
    });

});

Route::middleware('admin')->group(function () {
    Route::group([], function () {
        Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('chart/deposit-withdraw', [AdminController::class, 'depositAndWithdrawReport'])->name('chart.deposit.withdraw');
        Route::get('chart/transaction', [AdminController::class, 'transactionReport'])->name('chart.transaction');
        Route::get('profile', [AdminController::class, 'profile'])->name('profile');
        Route::post('profile', [AdminController::class, 'profileUpdate'])->name('profile.update');
        Route::get('password', [AdminController::class, 'password'])->name('password');
        Route::post('password', [AdminController::class, 'passwordUpdate'])->name('password.update');

        // Notifications
        Route::get('notifications', [AdminController::class, 'notifications'])->name('notifications');
        Route::get('notification/read/{id}', [AdminController::class, 'notificationRead'])->name('notification.read');
        Route::get('notifications/read-all', [AdminController::class, 'readAllNotification'])->name('notifications.read.all');
        Route::post('notifications/delete-all', [AdminController::class, 'deleteAllNotification'])->name('notifications.delete.all');
        Route::post('notifications/delete-single/{id}', [AdminController::class, 'deleteSingleNotification'])->name('notifications.delete.single');

        // Report Bugs
        Route::get('request-report', [AdminController::class, 'requestReport'])->name('request.report');
        Route::post('request-report', [AdminController::class, 'reportSubmit']);

        Route::get('download-attachments/{file_hash}', [AdminController::class, 'downloadAttachment'])->name('download.attachment');
    });
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [ManageUsersController::class, 'allUsers'])->name('all');
        Route::get('active', [ManageUsersController::class, 'activeUsers'])->name('active');
        Route::get('banned', [ManageUsersController::class, 'bannedUsers'])->name('banned');
        Route::get('email-verified', [ManageUsersController::class, 'emailVerifiedUsers'])->name('email.verified');
        Route::get('email-unverified', [ManageUsersController::class, 'emailUnverifiedUsers'])->name('email.unverified');
        Route::get('mobile-unverified', [ManageUsersController::class, 'mobileUnverifiedUsers'])->name('mobile.unverified');
        Route::get('kyc-unverified', [ManageUsersController::class, 'kycUnverifiedUsers'])->name('kyc.unverified');
        Route::get('kyc-pending', [ManageUsersController::class, 'kycPendingUsers'])->name('kyc.pending');
        Route::get('mobile-verified', [ManageUsersController::class, 'mobileVerifiedUsers'])->name('mobile.verified');
        Route::get('with-balance', [ManageUsersController::class, 'usersWithBalance'])->name('with.balance');

        Route::get('detail/{id}', [ManageUsersController::class, 'detail'])->name('detail');
        Route::get('kyc-data/{id}', [ManageUsersController::class, 'kycDetails'])->name('kyc.details');
        Route::post('kyc-approve/{id}', [ManageUsersController::class, 'kycApprove'])->name('kyc.approve');
        Route::post('kyc-reject/{id}', [ManageUsersController::class, 'kycReject'])->name('kyc.reject');
        Route::post('update/{id}', [ManageUsersController::class, 'update'])->name('update');
        Route::post('add-sub-balance/{id}', [ManageUsersController::class, 'addSubBalance'])->name('add.sub.balance');

        Route::get('send-notification/{id}', [ManageUsersController::class, 'showNotificationSingleForm'])->name('notification.single');
        Route::post('send-notification/{id}', [ManageUsersController::class, 'sendNotificationSingle'])->name('notification.single');
        Route::get('login/{id}', [ManageUsersController::class, 'login'])->name('login');
        Route::post('status/{id}', [ManageUsersController::class, 'status'])->name('status');

        Route::get('send-notification', [ManageUsersController::class, 'showNotificationAllForm'])->name('notification.all');
        Route::post('send-notification', [ManageUsersController::class, 'sendNotificationAll'])->name('notification.all.send');
        Route::get('list', [ManageUsersController::class, 'list'])->name('list');
        Route::get('count-by-segment/{methodName}', [ManageUsersController::class, 'countBySegment'])->name('segment.count');
        Route::get('notification-log/{id}', [ManageUsersController::class, 'notificationLog'])->name('notification.log');
    });
    // Deposit Gateway
    Route::name('gateway.')->prefix('gateway')->group(function () {
        // Automatic Gateway
        Route::prefix('automatic')->name('automatic.')->group(function () {
            Route::get('/', [AutomaticGatewayController::class, 'index'])->name('index');
            Route::get('edit/{alias}', [AutomaticGatewayController::class, 'edit'])->name('edit');
            Route::post('update/{code}', [AutomaticGatewayController::class, 'update'])->name('update');
            Route::post('remove/{id}', [AutomaticGatewayController::class, 'remove'])->name('remove');
            Route::post('status/{id}', [AutomaticGatewayController::class, 'status'])->name('status');
        });

        // Manual Methods
        Route::prefix('manual')->name('manual.')->group(function () {
            Route::get('/', [ManualGatewayController::class, 'index'])->name('index');
            Route::get('new', [ManualGatewayController::class, 'create'])->name('create');
            Route::post('new', [ManualGatewayController::class, 'store'])->name('store');
            Route::get('edit/{alias}', [ManualGatewayController::class, 'edit'])->name('edit');
            Route::post('update/{id}', [ManualGatewayController::class, 'update'])->name('update');
            Route::post('status/{id}', [ManualGatewayController::class, 'status'])->name('status');
        });
    });

    // DEPOSIT SYSTEM
    Route::prefix('deposit')->name('deposit.')->group(function () {
        Route::get('all/{user_id?}', [DepositController::class, 'deposit'])->name('list');
        Route::get('pending/{user_id?}', [DepositController::class, 'pending'])->name('pending');
        Route::get('rejected/{user_id?}', [DepositController::class, 'rejected'])->name('rejected');
        Route::get('approved/{user_id?}', [DepositController::class, 'approved'])->name('approved');
        Route::get('successful/{user_id?}', [DepositController::class, 'successful'])->name('successful');
        Route::get('initiated/{user_id?}', [DepositController::class, 'initiated'])->name('initiated');
        Route::get('details/{id}', [DepositController::class, 'details'])->name('details');
        Route::post('reject', [DepositController::class, 'reject'])->name('reject');
        Route::post('approve/{id}', [DepositController::class, 'approve'])->name('approve');
    });

    // WITHDRAW SYSTEM
    Route::name('withdraw.')->prefix('withdraw')->group(function () {
        Route::name('data.')->group(function () {
            Route::get('pending/{user_id?}', [WithdrawalController::class, 'pending'])->name('pending');
            Route::get('approved/{user_id?}', [WithdrawalController::class, 'approved'])->name('approved');
            Route::get('rejected/{user_id?}', [WithdrawalController::class, 'rejected'])->name('rejected');
            Route::get('all/{user_id?}', [WithdrawalController::class, 'all'])->name('all');
            Route::get('details/{id}', [WithdrawalController::class, 'details'])->name('details');
            Route::post('approve', [WithdrawalController::class, 'approve'])->name('approve');
            Route::post('reject', [WithdrawalController::class, 'reject'])->name('reject');
        });

        // Withdraw Method
        Route::prefix('method')->name('method.')->group(function () {
            Route::get('/', [WithdrawMethodController::class, 'methods'])->name('index');
            Route::get('create', [WithdrawMethodController::class, 'create'])->name('create');
            Route::post('create', [WithdrawMethodController::class, 'store'])->name('store');
            Route::get('edit/{id}', [WithdrawMethodController::class, 'edit'])->name('edit');
            Route::post('edit/{id}', [WithdrawMethodController::class, 'update'])->name('update');
            Route::post('status/{id}', [WithdrawMethodController::class, 'status'])->name('status');
        });
    });

    // Report
    Route::prefix('report')->name('report.')->group(function () {
        Route::get('transaction/{user_id?}', [ReportController::class, 'transaction'])->name('transaction');
        Route::get('login/history', [ReportController::class, 'loginHistory'])->name('login.history');
        Route::get('login/ipHistory/{ip}', [ReportController::class, 'loginIpHistory'])->name('login.ipHistory');
        Route::get('notification/history', [ReportController::class, 'notificationHistory'])->name('notification.history');
        Route::get('email/detail/{id}', [ReportController::class, 'emailDetails'])->name('email.details');
    });

    // Admin Support
    Route::prefix('ticket')->name('ticket.')->group(function () {
        Route::get('/', [SupportTicketController::class, 'tickets'])->name('index');
        Route::get('pending', [SupportTicketController::class, 'pendingTicket'])->name('pending');
        Route::get('closed', [SupportTicketController::class, 'closedTicket'])->name('closed');
        Route::get('answered', [SupportTicketController::class, 'answeredTicket'])->name('answered');
        Route::get('view/{id}', [SupportTicketController::class, 'ticketReply'])->name('view');
        Route::post('reply/{id}', [SupportTicketController::class, 'replyTicket'])->name('reply');
        Route::post('close/{id}', [SupportTicketController::class, 'closeTicket'])->name('close');
        Route::get('download/{attachment_id}', [SupportTicketController::class, 'ticketDownload'])->name('download');
        Route::post('delete/{id}', [SupportTicketController::class, 'ticketDelete'])->name('delete');
    });


    // Language Manager
    Route::prefix('language')->name('language.')->group(function () {
        Route::get('/', [LanguageController::class, 'langManage'])->name('manage');
        Route::post('/', [LanguageController::class, 'langStore'])->name('manage.store');
        Route::post('delete/{id}', [LanguageController::class, 'langDelete'])->name('manage.delete');
        Route::post('update/{id}', [LanguageController::class, 'langUpdate'])->name('manage.update');
        Route::get('edit/{id}', [LanguageController::class, 'langEdit'])->name('key');
        Route::post('import', [LanguageController::class, 'langImport'])->name('import.lang');
        Route::post('store/key/{id}', [LanguageController::class, 'storeLanguageJson'])->name('store.key');
        Route::post('delete/key/{id}', [LanguageController::class, 'deleteLanguageJson'])->name('delete.key');
        Route::post('update/key/{id}', [LanguageController::class, 'updateLanguageJson'])->name('update.key');
        Route::get('get-keys', [LanguageController::class, 'getKeys'])->name('get.key');
    });

    // General Settings
    Route::group([],function () {
        Route::get('system-setting', [GeneralSettingController::class, 'systemSetting'])->name('setting.system');

        // General Setting
        Route::get('general-setting', [GeneralSettingController::class, 'general'])->name('setting.general');
        Route::post('general-setting', [GeneralSettingController::class, 'generalUpdate']);

        // Social login
        Route::get('setting/social/credentials', [GeneralSettingController::class, 'socialiteCredentials'])->name('setting.socialite.credentials');
        Route::post('setting/social/credentials/update/{key}', [GeneralSettingController::class, 'updateSocialiteCredential'])->name('setting.socialite.credentials.update');
        Route::post('setting/social/credentials/status/{key}', [GeneralSettingController::class, 'updateSocialiteCredentialStatus'])->name('setting.socialite.credentials.status.update');

        // Configuration
        Route::get('setting/system-configuration', [GeneralSettingController::class, 'systemConfiguration'])->name('setting.system.configuration');
        Route::post('setting/system-configuration', [GeneralSettingController::class, 'systemConfigurationSubmit']);

        // Logo-Icon
        Route::get('setting/logo-icon', [GeneralSettingController::class, 'logoIcon'])->name('setting.logo.icon');
        Route::post('setting/logo-icon', [GeneralSettingController::class, 'logoIconUpdate'])->name('setting.logo.icon');

        // Custom CSS
        Route::get('custom-css', [GeneralSettingController::class, 'customCss'])->name('setting.custom.css');
        Route::post('custom-css', [GeneralSettingController::class, 'customCssSubmit']);

        Route::get('sitemap', [GeneralSettingController::class, 'sitemap'])->name('setting.sitemap');
        Route::post('sitemap', [GeneralSettingController::class, 'sitemapSubmit']);

        Route::get('robot', [GeneralSettingController::class, 'robot'])->name('setting.robot');
        Route::post('robot', [GeneralSettingController::class, 'robotSubmit']);

        // Cookie
        Route::get('cookie', [GeneralSettingController::class, 'cookie'])->name('setting.cookie');
        Route::post('cookie', [GeneralSettingController::class, 'cookieSubmit']);

        // Maintenance Mode
        Route::get('maintenance-mode', [GeneralSettingController::class, 'maintenanceMode'])->name('maintenance.mode');
        Route::post('maintenance-mode', [GeneralSettingController::class, 'maintenanceModeSubmit']);

        // In-app Purchase
        Route::get('in-app-purchase', [GeneralSettingController::class, 'inAppPurchase'])->name('setting.app.purchase');
        Route::post('in-app-purchase', [GeneralSettingController::class, 'inAppPurchaseConfigure']);
        Route::get('in-app-purchase/file/download', [GeneralSettingController::class, 'inAppPurchaseFileDownload'])->name('setting.app.purchase.file.download');
    });

    // KYC Setting
    Route::group([],function () {
        Route::get('kyc-setting', [KycController::class, 'setting'])->name('kyc.setting');
        Route::post('kyc-setting', [KycController::class, 'settingUpdate']);
    });

    // Category Management
    Route::prefix('category')->name('category.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('create', [CategoryController::class, 'create'])->name('create');
        Route::post('store/{id?}', [CategoryController::class, 'store'])->name('store');
        Route::get('edit/{id}', [CategoryController::class, 'edit'])->name('edit');
        Route::post('featured/{id}', [CategoryController::class, 'featured'])->name('featured');
        Route::post('status/{id}', [CategoryController::class, 'status'])->name('status');
    });

    // Manage SubCategory
    Route::prefix('sub-category')->name('subcategory.')->group(function () {
        Route::get('/', [SubCategoryController::class, 'index'])->name('index');
        Route::get('create', [SubCategoryController::class, 'create'])->name('create');
        Route::post('store/{id?}', [SubCategoryController::class, 'store'])->name('store');
        Route::post('status/{id}', [SubCategoryController::class, 'status'])->name('status');
        Route::get('edit/{id}', [SubCategoryController::class, 'edit'])->name('edit');
    });
    // File Type Routes
    Route::prefix('filetype')->name('filetype.')->group(function () {
        Route::get('/', [FileTypeController::class, 'index'])->name('index');
        Route::post('status/{id}', [FileTypeController::class, 'status'])->name('status');
        Route::post('store/{id?}', [FileTypeController::class, 'store'])->name('store');
    });

    // Job Routes
    Route::prefix('job')->name('jobs.')->group(function () {
        Route::get('/', [JobController::class, 'index'])->name('index');
        Route::get('pending', [JobController::class, 'pending'])->name('pending');
        Route::get('approved', [JobController::class, 'approved'])->name('approved');
        Route::get('complete', [JobController::class, 'complete'])->name('complete');
        Route::get('rejected', [JobController::class, 'rejected'])->name('rejected');
        Route::get('view/{id}', [JobController::class, 'view'])->name('view');
        Route::post('approve/{id}', [JobController::class, 'approve'])->name('approve');
        Route::post('reject/{id}', [JobController::class, 'reject'])->name('reject');
        Route::get('details/{id}', [JobController::class, 'details'])->name('details');
    });

    // Notification Setting Routes
    Route::prefix('notification')->name('setting.notification.')->group(function () {
        Route::get('global/email', [NotificationController::class, 'globalEmail'])->name('global.email');
        Route::post('global/email/update', [NotificationController::class, 'globalEmailUpdate'])->name('global.email.update');

        Route::get('global/sms', [NotificationController::class, 'globalSms'])->name('global.sms');
        Route::post('global/sms/update', [NotificationController::class, 'globalSmsUpdate'])->name('global.sms.update');

        Route::get('global/push', [NotificationController::class, 'globalPush'])->name('global.push');
        Route::post('global/push/update', [NotificationController::class, 'globalPushUpdate'])->name('global.push.update');

        Route::get('templates', [NotificationController::class, 'templates'])->name('templates');
        Route::get('template/edit/{type}/{id}', [NotificationController::class, 'templateEdit'])->name('template.edit');
        Route::post('template/update/{type}/{id}', [NotificationController::class, 'templateUpdate'])->name('template.update');

        Route::get('email/setting', [NotificationController::class, 'emailSetting'])->name('email');
        Route::post('email/setting', [NotificationController::class, 'emailSettingUpdate']);
        Route::post('email/test', [NotificationController::class, 'emailTest'])->name('email.test');

        Route::get('sms/setting', [NotificationController::class, 'smsSetting'])->name('sms');
        Route::post('sms/setting', [NotificationController::class, 'smsSettingUpdate']);
        Route::post('sms/test', [NotificationController::class, 'smsTest'])->name('sms.test');

        Route::get('notification/push/setting', [NotificationController::class, 'pushSetting'])->name('push');
        Route::post('notification/push/setting', [NotificationController::class, 'pushSettingUpdate']);
        Route::post('notification/push/setting/upload', [NotificationController::class, 'pushSettingUpload'])->name('push.upload');
        Route::get('notification/push/setting/download', [NotificationController::class, 'pushSettingDownload'])->name('push.download');
    });

    // Plugin Routes
    Route::prefix('extensions')->name('extensions.')->group(function () {
        Route::get('/', [ExtensionController::class, 'index'])->name('index');
        Route::post('update/{id}', [ExtensionController::class, 'update'])->name('update');
        Route::post('status/{id}', [ExtensionController::class, 'status'])->name('status');
    });

    // System Information Routes
    Route::prefix('system')->name('system.')->group(function () {
        Route::get('info', [SystemController::class, 'systemInfo'])->name('info');
        Route::get('server-info', [SystemController::class, 'systemServerInfo'])->name('server.info');
        Route::get('optimize', [SystemController::class, 'optimize'])->name('optimize');
        Route::get('optimize-clear', [SystemController::class, 'optimizeClear'])->name('optimize.clear');
        Route::get('system-update', [SystemController::class, 'systemUpdate'])->name('update');
        Route::post('system-update', [SystemController::class, 'systemUpdateProcess'])->name('update.process');
        Route::get('system-update/log', [SystemController::class, 'systemUpdateLog'])->name('update.log');
    });

    // SEO Route
    Route::get('seo', [FrontendController::class, 'seoEdit'])->name('seo');

    // Frontend Routes
    Route::prefix('frontend')->name('frontend.')->group(function () {
        Route::get('index', [FrontendController::class, 'index'])->name('index');
        Route::get('templates', [FrontendController::class, 'templates'])->name('templates');
        Route::post('templates', [FrontendController::class, 'templatesActive'])->name('templates.active');
        Route::get('frontend-sections/{key?}', [FrontendController::class, 'frontendSections'])->name('sections');
        Route::post('frontend-content/{key}', [FrontendController::class, 'frontendContent'])->name('sections.content');
        Route::get('frontend-element/{key}/{id?}', [FrontendController::class, 'frontendElement'])->name('sections.element');
        Route::get('frontend-slug-check/{key}/{id?}', [FrontendController::class, 'frontendElementSlugCheck'])->name('sections.element.slug.check');
        Route::get('frontend-element-seo/{key}/{id}', [FrontendController::class, 'frontendSeo'])->name('sections.element.seo');
        Route::post('frontend-element-seo/{key}/{id}', [FrontendController::class, 'frontendSeoUpdate']);
        Route::post('remove/{id}', [FrontendController::class, 'remove'])->name('remove');
    });

    // Page Builder Routes
    Route::prefix('frontend')->name('frontend.')->group(function () {
        Route::get('manage-pages', [PageBuilderController::class, 'managePages'])->name('manage.pages');
        Route::get('manage-pages/check-slug/{id?}', [PageBuilderController::class, 'checkSlug'])->name('manage.pages.check.slug');
        Route::post('manage-pages', [PageBuilderController::class, 'managePagesSave'])->name('manage.pages.save');
        Route::post('manage-pages/update', [PageBuilderController::class, 'managePagesUpdate'])->name('manage.pages.update');
        Route::post('manage-pages/delete/{id}', [PageBuilderController::class, 'managePagesDelete'])->name('manage.pages.delete');
        Route::get('manage-section/{id}', [PageBuilderController::class, 'manageSection'])->name('manage.section');
        Route::post('manage-section/{id}', [PageBuilderController::class, 'manageSectionUpdate'])->name('manage.section.update');
        Route::get('manage-seo/{id}', [PageBuilderController::class, 'manageSeo'])->name('manage.pages.seo');
        Route::post('manage-seo/{id}', [PageBuilderController::class, 'manageSeoStore']);
    });
});
