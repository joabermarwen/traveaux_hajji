<?php

use App\Http\Controllers\Gateway\PaymentController;
use App\Http\Controllers\User\Auth\LoginController;
use App\Http\Controllers\User\Auth\RegisterController;
use App\Http\Controllers\User\Auth\ForgotPasswordController;
use App\Http\Controllers\User\Auth\ResetPasswordController;
use App\Http\Controllers\User\Auth\SocialiteController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\AuthorizationController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\JobPostController;
use App\Http\Controllers\User\WithdrawController;
use Illuminate\Support\Facades\Route;

// Guest Middleware Group
Route::middleware('guest')->name('user.')->group(function () {
    // Login Routes
    Route::controller(LoginController::class)->group(function () {
        Route::get('/login', 'showLoginForm')->name('login');
        Route::post('/login', 'login')->name('post-login');
        Route::get('logout', 'logout')
            ->middleware('auth')
            ->withoutMiddleware('guest')
            ->name('logout');
    });

    // Registration Routes
    Route::controller(RegisterController::class)->group(function () {
        Route::get('register', 'showRegistrationForm')->name('register');
        Route::post('register', 'register');
        Route::post('check-user', 'checkUser')
            ->name('checkUser')
            ->withoutMiddleware('guest');
    });

    // Forgot Password Routes
    Route::controller(ForgotPasswordController::class)
        ->prefix('password')
        ->name('password.')
        ->group(function () {
            Route::get('reset', 'showLinkRequestForm')->name('request');
            Route::post('email', 'sendResetCodeEmail')->name('email');
            Route::get('code-verify', 'codeVerify')->name('code.verify');
            Route::post('verify-code', 'verifyCode')->name('verify.code');
        });

    // Reset Password Routes
    Route::controller(ResetPasswordController::class)->group(function () {
        Route::post('password/reset', 'reset')->name('password.update');
        Route::get('password/reset/{token}', 'showResetForm')->name('password.reset');
    });

    // Socialite Routes
    Route::controller(SocialiteController::class)->group(function () {
        Route::get('social-login/{provider}', 'socialLogin')->name('social.login');
        Route::get('social-login/callback/{provider}', 'callback')->name('social.login.callback');
    });
});

// Auth Middleware Group
Route::middleware('auth')->name('user.')->group(function () {
    // User Data Routes
    Route::get('user-data', [UserController::class, 'userData'])->name('data');
    Route::post('user-data-submit', [UserController::class, 'userDataSubmit'])->name('data.submit');

    // Authorization Middleware Group
    Route::middleware('registration.complete')->group(function () {
        Route::controller(AuthorizationController::class)->group(function () {
            Route::get('authorization', 'authorizeForm')->name('authorization');
            Route::get('resend-verify/{type}', 'sendVerifyCode')->name('send.verify.code');
            Route::post('verify-email', 'emailVerification')->name('verify.email');
            Route::post('verify-mobile', 'mobileVerification')->name('verify.mobile');
            Route::post('verify-g2fa', 'g2faVerification')->name('2fa.verify');
        });

        // Check Status Middleware Group
        Route::middleware(['check.status', 'registration.complete'])->group(function () {
            // User Dashboard Routes
            Route::controller(UserController::class)->group(function () {
                Route::get('dashboard', 'home')->name('home');
                Route::get('download-attachments/{file_hash}', 'downloadAttachment')->name('download.attachment');

                // 2FA Routes
                Route::get('twofactor', 'show2faForm')->name('twofactor');
                Route::post('twofactor/enable', 'create2fa')->name('twofactor.enable');
                Route::post('twofactor/disable', 'disable2fa')->name('twofactor.disable');

                // KYC Routes
                Route::get('kyc-form', 'kycForm')->name('kyc.form');
                Route::get('kyc-data', 'kycData')->name('kyc.data');
                Route::post('kyc-submit', 'kycSubmit')->name('kyc.submit');

                // Report Routes
                Route::any('deposit/history', 'depositHistory')->name('deposit.history');
                Route::get('transactions', 'transactions')->name('transactions');

                Route::post('add-device-token', 'addDeviceToken')->name('add.device.token');
            });

            // Profile Settings Routes
            Route::controller(ProfileController::class)->group(function () {
                Route::get('profile-setting', 'profile')->name('profile.setting');
                Route::post('profile-setting', 'submitProfile');
                Route::get('change-password', 'changePassword')->name('change.password');
                Route::post('change-password', 'submitPassword');
            });

            // Job Post Routes
            Route::controller(JobPostController::class)
                ->prefix('job')
                ->name('job.')
                ->group(function () {
                    Route::get('create', 'create')->name('create');
                    Route::post('store', 'store')->name('store');
                    Route::get('history', 'history')->name('history');
                    Route::get('edit/{id}', 'edit')->name('edit');
                    Route::post('update/{id}', 'update')->name('update');
                    Route::post('prove/{id}', 'prove')->name('prove');
                    Route::post('status/{id}', 'status')->name('status');
                    Route::get('details/{id}', 'details')->name('details');
                    Route::get('finished', 'finished')->name('finished');
                    Route::get('apply', 'apply')->name('apply');
                    Route::get('attachment/{id}', 'attachment')->name('attachment');
                    Route::get('attachment/download/{id}', 'downloadAttachment')->name('download.attachment');
                    Route::post('approve/{id}', 'approve')->name('approve');
                    Route::post('reject/{id}', 'reject')->name('reject');
                });

            // Withdraw Routes
            Route::controller(WithdrawController::class)
                ->prefix('withdraw')
                ->name('withdraw')
                ->group(function () {
                    Route::middleware('kyc')->group(function () {
                        Route::get('/', 'withdrawMoney');
                        Route::post('/', 'withdrawStore')->name('.money');
                        Route::get('preview', 'withdrawPreview')->name('.preview');
                        Route::post('preview', 'withdrawSubmit')->name('.submit');
                    });
                    Route::get('history', 'withdrawLog')->name('.history');
                });

            // Payment Routes
            Route::controller(PaymentController::class)
                ->prefix('deposit')
                ->name('deposit.')
                ->group(function () {
                    Route::any('/', 'deposit')->name('index');
                    Route::post('insert', 'depositInsert')->name('insert');
                    Route::get('confirm', 'depositConfirm')->name('confirm');
                    Route::get('manual', 'manualDepositConfirm')->name('manual.confirm');
                    Route::post('manual', 'manualDepositUpdate')->name('manual.update');
                });
        });
    });
});
