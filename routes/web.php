<?php

use Illuminate\Support\Facades\Route;

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

Route::prefix('/')->group(function() {
    Route::get('/', [App\Http\Controllers\Guest\GuestController::class, 'index'])->name('home');
});

// system
Route::prefix('/dashboard')->middleware('check.allowed.ip')->group(function() {

    // admin login, logout system
    Route::group(['middleware' => ['log.admin.login']], function () {
        Route::get('/login', [App\Http\Controllers\Backend\Auth\LoginController::class, 'showLoginForm'])->name('admin.login');
        Route::post('/login', [App\Http\Controllers\Backend\Auth\LoginController::class, 'login'])->name('admin.login.submit');

        // Route::get('/register', [App\Http\Controllers\Backend\Auth\RegisterController::class, 'showRegisterForm'])->name('admin.register');
        // Route::post('/register', [App\Http\Controllers\Backend\Auth\RegisterController::class, 'register'])->name('admin.register.submit');

        // Route::get('/password/reset', [App\Http\Controllers\Backend\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('admin.password.reset');
        // Route::get('/password/reset/{token}', [App\Http\Controllers\Backend\Auth\ResetPasswordController::class, 'showResetForm'])->name('admin.password.reset.token.submit');
        // Route::post('/password/reset', [App\Http\Controllers\Backend\Auth\ResetPasswordController::class, 'reset'])->name('admin.password.reset.submit');
        
        // Route::post('/password/email', [App\Http\Controllers\Backend\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('admin.password.email.submit');

        Route::post('/logout', [App\Http\Controllers\Backend\Auth\LoginController::class, 'logout'])->name('admin.logout');
    });
    // admin login, logout system

    // ajax
    // sidebar
    Route::get('/ajax-left-sidebar-status', [App\Http\Controllers\Backend\Dashboard\DashboardController::class, 'left_aside_status']);
    // sidebar

    // cropper image upload
    Route::any('/cropper-image-upload', [App\Http\Controllers\Common\CommonController::class, 'cropper_image_upload'])->name('ajaxImageUpload');
    Route::any('/editor-image-upload', [App\Http\Controllers\Common\CommonController::class, 'editor_image_upload'])->name('ckeditor.upload');
    // cropper image upload

    // data fetch url
    Route::get('/ajax-fetch-customer', [App\Http\Controllers\Common\CommonController::class, 'ajax_fetch_customer'])->name('ajaxCustomerDataFetch');
    Route::get('/ajax-fetch-cashier', [App\Http\Controllers\Common\CommonController::class, 'ajax_fetch_cashier'])->name('ajaxCashierDataFetch');
    
    Route::get('/ajax-fetch-vehicle', [App\Http\Controllers\Common\CommonController::class, 'ajax_fetch_vehicle'])->name('ajaxVehicleDataFetch');
    Route::get('/ajax-fetch-vehicle-details', [App\Http\Controllers\Common\CommonController::class, 'ajax_fetch_vehicle_data'])->name('ajaxVehicleDataFetchById');
    
    Route::get('/ajax-fetch-service-person', [App\Http\Controllers\Common\CommonController::class, 'ajax_fetch_service_person'])->name('ajaxServicePersonDataFetch');
    
    Route::get('/ajax-fetch-products', [App\Http\Controllers\Common\CommonController::class, 'ajax_fetch_products'])->name('ajaxProductDataFetch');
    Route::get('/ajax-fetch-products-details', [App\Http\Controllers\Common\CommonController::class, 'ajax_fetch_products_data'])->name('ajaxProductDataFetchById');
    
    Route::get('/ajax-fetch-payment-types', [App\Http\Controllers\Common\CommonController::class, 'ajax_fetch_payment_types'])->name('ajaxPaymentTypeDataFetch');
    Route::get('/ajax-fetch-orders', [App\Http\Controllers\Common\CommonController::class, 'ajax_fetch_orders'])->name('ajaxOrderDataFetch');
    // data fetch url
    // ajax

    // dashboard
    Route::get('/', [App\Http\Controllers\Backend\Dashboard\DashboardController::class, 'index'])->name('admin.home');
    // dashboard

    // admin my profile
    Route::prefix('/profile')->group(function() {
        Route::get('/', [App\Http\Controllers\Backend\Profile\ProfileController::class, 'index'])->name('adminProfile');
        
        Route::post('/', [App\Http\Controllers\Backend\Profile\ProfileController::class, 'profile_update'])->name('adminProfileUpdate');
        Route::post('/password', [App\Http\Controllers\Backend\Profile\ProfileController::class, 'password_update'])->name('adminProfilePasswordUpdate');
        
        Route::post('/generate-access-token', [App\Http\Controllers\Backend\Profile\ProfileController::class, 'generate_token'])->name('adminProfileGenerateAccessToken');
        Route::get('/remove-access-token/{data}', [App\Http\Controllers\Backend\Profile\ProfileController::class, 'remove_token'])->name('adminProfileRemoveAccessToken');

        Route::get('/ajax-save-profile-picture', [App\Http\Controllers\Backend\Profile\ProfileController::class, 'ajax_profile_picture_save']);
    });
    // admin my profile

    // order
    Route::prefix('/order')->group(function() {
        // receipt
        Route::get('/receipt-base/{data}/download', [App\Http\Controllers\Backend\Order\OrderController::class, 'receipt_base_download'])->name('admin.order.receipt.base.download');
        Route::get('/receipt-list/{data}/download', [App\Http\Controllers\Backend\Order\OrderController::class, 'receipt_list_download'])->name('admin.order.receipt.list.download');

        Route::get('/receipt-base/{data}', [App\Http\Controllers\Backend\Order\OrderController::class, 'receipt_base'])->name('admin.order.receipt.base');
        Route::get('/receipt-list/{data}', [App\Http\Controllers\Backend\Order\OrderController::class, 'receipt_list'])->name('admin.order.receipt.list');
        // receipt

        // create order
        Route::get('/create', [App\Http\Controllers\Backend\Order\OrderController::class, 'create'])->name('admin.order.create');
        Route::post('/create', [App\Http\Controllers\Backend\Order\OrderController::class, 'create_post'])->name('admin.order.create.post');
        // create order

        // view order
        Route::get('/view/{data}', [App\Http\Controllers\Backend\Order\OrderController::class, 'view'])->name('admin.order.view');
        // view order

        // edit order
        Route::get('/edit/{data}', [App\Http\Controllers\Backend\Order\OrderController::class, 'update'])->name('admin.order.edit');
        Route::post('/edit/{data}', [App\Http\Controllers\Backend\Order\OrderController::class, 'update_post'])->name('admin.order.edit.post');
        // edit order

        // remove order
        Route::get('/remove/{data}/history/{item}', [App\Http\Controllers\Backend\Order\OrderController::class, 'remove_history_item'])->name('admin.order.remove.history');

        Route::get('/remove/{data}/product/{item}', [App\Http\Controllers\Backend\Order\OrderController::class, 'remove_product_item'])->name('admin.order.remove.product');
        Route::get('/remove/{data}/payment/{item}', [App\Http\Controllers\Backend\Order\OrderController::class, 'remove_payment_item'])->name('admin.order.remove.payment');
        Route::get('/remove/{data}/comment/{item}', [App\Http\Controllers\Backend\Order\OrderController::class, 'remove_comment_item'])->name('admin.order.remove.comment');

        Route::get('/remove/{data}', [App\Http\Controllers\Backend\Order\OrderController::class, 'remove'])->name('admin.order.remove');
        Route::get('/remove-selected', [App\Http\Controllers\Backend\Order\OrderController::class, 'remove_multiple'])->name('admin.order.remove.all');
        // remove order        

        // trashed
        // products item
        Route::get('/trashed-products/{data}', [App\Http\Controllers\Backend\Order\OrderController::class, 'trashed_product_records'])->name('admin.order.product.trashed');
        Route::get('/restore/{data}/product/{item}', [App\Http\Controllers\Backend\Order\OrderController::class, 'trashed_product_restore'])->name('admin.order.product.trashed.restore');
        // products item

        // payment item
        Route::get('/trashed-payments/{data}', [App\Http\Controllers\Backend\Order\OrderController::class, 'trashed_payment_records'])->name('admin.order.payment.trashed');
        Route::get('/restore/{data}/payment/{item}', [App\Http\Controllers\Backend\Order\OrderController::class, 'trashed_payment_restore'])->name('admin.order.payment.trashed.restore');
        // payment item

        // comment
        Route::get('/trashed-comments/{data}', [App\Http\Controllers\Backend\Order\OrderController::class, 'trashed_comment_records'])->name('admin.order.comment.trashed');
        Route::get('/restore/{data}/comment/{item}', [App\Http\Controllers\Backend\Order\OrderController::class, 'trashed_comment_restore'])->name('admin.order.comment.trashed.restore');
        // comment

        Route::get('/trashed', [App\Http\Controllers\Backend\Order\OrderController::class, 'trashed_records'])->name('admin.order.records.trashed');
        Route::get('/restore/{data}', [App\Http\Controllers\Backend\Order\OrderController::class, 'trashed_record_restore'])->name('admin.order.records.trashed.restore');
        // trashed

        // records
        Route::get('/{from?}/{to?}/{paid?}/{due?}/{status?}', [App\Http\Controllers\Backend\Order\OrderController::class, 'records'])->name('admin.order.records');
        // records
    });
    // order

    // customer
    Route::prefix('/customer')->group(function() {

        // create customer
        Route::get('/create', [App\Http\Controllers\Backend\Customer\CustomerController::class, 'create'])->name('customerCreate');
        Route::post('/create', [App\Http\Controllers\Backend\Customer\CustomerController::class, 'create_post'])->name('customerCreatePost');
        // create customer

        // view customer
        Route::get('/view/{data}', [App\Http\Controllers\Backend\Customer\CustomerController::class, 'view'])->name('customerView');
        // view customer

        // edit customer
        Route::get('/edit/{data}', [App\Http\Controllers\Backend\Customer\CustomerController::class, 'update'])->name('customerUpdate');
        Route::post('/edit/{data}', [App\Http\Controllers\Backend\Customer\CustomerController::class, 'update_post'])->name('customerUpdatePost');
        // edit customer

        // remove customer
        Route::get('/remove/{data}/history/{item}', [App\Http\Controllers\Backend\Customer\CustomerController::class, 'remove_history_item'])->name('customerHistoryItemRemove');

        Route::get('/remove/{data}', [App\Http\Controllers\Backend\Customer\CustomerController::class, 'remove'])->name('customerRemove');
        Route::get('/remove-selected', [App\Http\Controllers\Backend\Customer\CustomerController::class, 'remove_multiple'])->name('ajaxCustomerRemoveMulti');
        // remove customer

        // admins status
        Route::get('/status-change', [App\Http\Controllers\Backend\Customer\CustomerController::class, 'status']);
        // admins status

        // records
        Route::get('/', [App\Http\Controllers\Backend\Customer\CustomerController::class, 'records'])->name('customerRecordsPage');
        // records

        // trashed
        Route::get('/trashed-comments/{data}', [App\Http\Controllers\Backend\Customer\CustomerController::class, 'trashed_comment_records'])->name('customerTrashCommentRecordsPage');
        Route::get('/restore/{data}/comment/{item}', [App\Http\Controllers\Backend\Customer\CustomerController::class, 'trashed_comment_restore'])->name('customerTrashCommentRestore');

        Route::get('/trashed', [App\Http\Controllers\Backend\Customer\CustomerController::class, 'trashed_records'])->name('customerTrashRecordsPage');
        Route::get('/restore/{data}', [App\Http\Controllers\Backend\Customer\CustomerController::class, 'trashed_record_restore'])->name('customerTrashRecordsRestore');
        // trashed
    });
    // customer

    // vehicle
    Route::prefix('/vehicle')->group(function() {

        // create vehicle
        Route::get('/create', [App\Http\Controllers\Backend\Vehicle\VehicleController::class, 'create'])->name('vehicleCreate');
        Route::post('/create', [App\Http\Controllers\Backend\Vehicle\VehicleController::class, 'create_post'])->name('vehicleCreatePost');
        // create vehicle

        // view vehicle
        Route::get('/view/{data}', [App\Http\Controllers\Backend\Vehicle\VehicleController::class, 'view'])->name('vehicleView');
        // view vehicle

        // edit vehicle
        Route::get('/edit/{data}', [App\Http\Controllers\Backend\Vehicle\VehicleController::class, 'update'])->name('vehicleUpdate');
        Route::post('/edit/{data}', [App\Http\Controllers\Backend\Vehicle\VehicleController::class, 'update_post'])->name('vehicleUpdatePost');
        // edit vehicle

        // remove vehicle
        Route::get('/remove/{data}/history/{item}', [App\Http\Controllers\Backend\Vehicle\VehicleController::class, 'remove_history_item'])->name('vehicleHistoryItemRemove');

        Route::get('/remove/{data}', [App\Http\Controllers\Backend\Vehicle\VehicleController::class, 'remove'])->name('vehicleRemove');
        Route::get('/remove-selected', [App\Http\Controllers\Backend\Vehicle\VehicleController::class, 'remove_multiple'])->name('ajaxVehicleRemoveMulti');
        // remove vehicle

        // admins status
        Route::get('/status-change', [App\Http\Controllers\Backend\Vehicle\VehicleController::class, 'status']);
        // admins status

        // records
        Route::get('/', [App\Http\Controllers\Backend\Vehicle\VehicleController::class, 'records'])->name('vehicleRecordsPage');
        // records

        // trashed
        Route::get('/trashed', [App\Http\Controllers\Backend\Vehicle\VehicleController::class, 'trashed_records'])->name('vehicleTrashRecordsPage');
        Route::get('/restore/{data}', [App\Http\Controllers\Backend\Vehicle\VehicleController::class, 'trashed_record_restore'])->name('vehicleTrashRecordsRestore');
        // trashed
    });
    // vehicle

    // product
    Route::prefix('/product')->group(function() {

        // create product
        Route::get('/create', [App\Http\Controllers\Backend\Product\ProductController::class, 'create'])->name('productCreate');
        Route::post('/create', [App\Http\Controllers\Backend\Product\ProductController::class, 'create_post'])->name('productCreatePost');
        // create product

        // view product
        Route::get('/view/{data}', [App\Http\Controllers\Backend\Product\ProductController::class, 'view'])->name('productView');
        // view product

        // edit product
        Route::get('/edit/{data}', [App\Http\Controllers\Backend\Product\ProductController::class, 'update'])->name('productUpdate');
        Route::post('/edit/{data}', [App\Http\Controllers\Backend\Product\ProductController::class, 'update_post'])->name('productUpdatePost');
        // edit product

        // remove product
        Route::get('/remove/{data}/history/{item}', [App\Http\Controllers\Backend\Product\ProductController::class, 'remove_history_item'])->name('productHistoryItemRemove');

        Route::get('/remove/{data}', [App\Http\Controllers\Backend\Product\ProductController::class, 'remove'])->name('productRemove');
        Route::get('/remove-selected', [App\Http\Controllers\Backend\Product\ProductController::class, 'remove_multiple'])->name('ajaxProductRemoveMulti');
        // remove product

        // admins status
        Route::get('/status-change', [App\Http\Controllers\Backend\Product\ProductController::class, 'status']);
        // admins status

        // records
        Route::get('/', [App\Http\Controllers\Backend\Product\ProductController::class, 'records'])->name('productRecordsPage');
        // records

        // trashed
        Route::get('/trashed', [App\Http\Controllers\Backend\Product\ProductController::class, 'trashed_records'])->name('productTrashRecordsPage');
        Route::get('/restore/{data}', [App\Http\Controllers\Backend\Product\ProductController::class, 'trashed_record_restore'])->name('productTrashRecordsRestore');
        // trashed
    });
    // product

    // payment
    Route::prefix('/payment')->group(function() {
        // create payment
        Route::get('/create', [App\Http\Controllers\Backend\Payment\PaymentController::class, 'create'])->name('paymentCreate');
        Route::post('/create', [App\Http\Controllers\Backend\Payment\PaymentController::class, 'create_post'])->name('paymentCreatePost');
        // create payment

        // view payment
        Route::get('/view/{data}', [App\Http\Controllers\Backend\Payment\PaymentController::class, 'view'])->name('paymentView');
        // view payment

        // edit payment
        Route::get('/edit/{data}', [App\Http\Controllers\Backend\Payment\PaymentController::class, 'update'])->name('paymentUpdate');
        Route::post('/edit/{data}', [App\Http\Controllers\Backend\Payment\PaymentController::class, 'update_post'])->name('paymentUpdatePost');
        // edit payment

        // remove payment
        Route::get('/remove/{data}/history/{item}', [App\Http\Controllers\Backend\Payment\PaymentController::class, 'remove_history_item'])->name('paymentHistoryItemRemove');

        Route::get('/remove/{data}', [App\Http\Controllers\Backend\Payment\PaymentController::class, 'remove'])->name('paymentRemove');
        Route::get('/remove-selected', [App\Http\Controllers\Backend\Payment\PaymentController::class, 'remove_multiple'])->name('ajaxPaymentRemoveMulti');
        // remove payment

        // admins status
        Route::get('/status-change', [App\Http\Controllers\Backend\Payment\PaymentController::class, 'status']);
        // admins status

        // records
        Route::get('/', [App\Http\Controllers\Backend\Payment\PaymentController::class, 'records'])->name('paymentRecordsPage');
        // records

        // trashed
        Route::get('/trashed', [App\Http\Controllers\Backend\Payment\PaymentController::class, 'trashed_records'])->name('paymentTrashRecordsPage');
        Route::get('/restore/{data}', [App\Http\Controllers\Backend\Payment\PaymentController::class, 'trashed_record_restore'])->name('paymentTrashRecordsRestore');
        // trashed

        // type
        Route::prefix('/types')->group(function() {
            // view paymentType
            Route::get('/view/{data}', [App\Http\Controllers\Backend\Payment\PaymentTypeController::class, 'view'])->name('paymentTypeView');
            // view paymentType

            // edit paymentType
            Route::get('/edit/{data}', [App\Http\Controllers\Backend\Payment\PaymentTypeController::class, 'update'])->name('paymentTypeUpdate');
            Route::post('/edit/{data}', [App\Http\Controllers\Backend\Payment\PaymentTypeController::class, 'update_post'])->name('paymentTypeUpdatePost');
            // edit paymentType

            // remove paymentType
            Route::get('/remove/{data}/history/{item}', [App\Http\Controllers\Backend\Payment\PaymentTypeController::class, 'remove_history_item'])->name('paymentTypeHistoryItemRemove');

            Route::get('/remove/{data}', [App\Http\Controllers\Backend\Payment\PaymentTypeController::class, 'remove'])->name('paymentTypeRemove');
            Route::get('/remove-selected', [App\Http\Controllers\Backend\Payment\PaymentTypeController::class, 'remove_multiple'])->name('ajaxPaymentTypeRemoveMulti');
            // remove paymentType

            // records
            Route::get('/', [App\Http\Controllers\Backend\Payment\PaymentTypeController::class, 'index'])->name('paymentTypeRecordsPage');
            Route::post('/', [App\Http\Controllers\Backend\Payment\PaymentTypeController::class, 'index_post'])->name('paymentTypeRecordsPagePost');
            // records

            // trashed
            Route::get('/trashed', [App\Http\Controllers\Backend\Payment\PaymentTypeController::class, 'trashed_records'])->name('paymentTypeTrashRecordsPage');
            Route::get('/restore/{data}', [App\Http\Controllers\Backend\Payment\PaymentTypeController::class, 'trashed_record_restore'])->name('paymentTypeTrashRecordsRestore');
            // trashed
        });
        // type
    });
    // payment

    // ip allow list
    Route::prefix('/ip-allow-list')->group(function() {
        // view paymentType
        Route::get('/view/{data}', [App\Http\Controllers\Backend\IpAllowList\IpAllowListController::class, 'view'])->name('ipAllowListView');
        // view ipAllowList

        // edit ipAllowList
        Route::get('/edit/{data}', [App\Http\Controllers\Backend\IpAllowList\IpAllowListController::class, 'update'])->name('ipAllowListUpdate');
        Route::post('/edit/{data}', [App\Http\Controllers\Backend\IpAllowList\IpAllowListController::class, 'update_post'])->name('ipAllowListUpdatePost');
        // edit ipAllowList

        // remove ipAllowList
        Route::get('/remove/{data}/history/{item}', [App\Http\Controllers\Backend\IpAllowList\IpAllowListController::class, 'remove_history_item'])->name('ipAllowListHistoryItemRemove');

        Route::get('/remove/{data}', [App\Http\Controllers\Backend\IpAllowList\IpAllowListController::class, 'remove'])->name('ipAllowListRemove');
        Route::get('/remove-selected', [App\Http\Controllers\Backend\IpAllowList\IpAllowListController::class, 'remove_multiple'])->name('ajaxIpAllowListRemoveMulti');
        // remove ipAllowList

        // records
        Route::get('/', [App\Http\Controllers\Backend\IpAllowList\IpAllowListController::class, 'index'])->name('ipAllowListRecordsPage');
        Route::post('/', [App\Http\Controllers\Backend\IpAllowList\IpAllowListController::class, 'index_post'])->name('ipAllowListRecordsPagePost');
        // records

        // trashed
        Route::get('/trashed', [App\Http\Controllers\Backend\IpAllowList\IpAllowListController::class, 'trashed_records'])->name('ipAllowListTrashRecordsPage');
        Route::get('/restore/{data}', [App\Http\Controllers\Backend\IpAllowList\IpAllowListController::class, 'trashed_record_restore'])->name('ipAllowListTrashRecordsRestore');
        // trashed
    });
    // ip allow list

    // notification
    Route::prefix('/notification')->group(function() {
        Route::get('/temp', [App\Http\Controllers\Backend\Notification\NotificationController::class, 'temp']);

        // send notification
        Route::get('/send/{customer_id?}/{type?}/{greeting?}/{subject?}/{text?}', [App\Http\Controllers\Backend\Notification\NotificationController::class, 'create'])->name('notificationCreate');
        Route::post('/send', [App\Http\Controllers\Backend\Notification\NotificationController::class, 'create_post'])->name('notificationCreatePost');
        // send notification

        // view notification
        Route::get('/view/{data}', [App\Http\Controllers\Backend\Notification\NotificationController::class, 'view'])->name('notificationView');
        // view notification

        // remove notification
        Route::get('/remove/{data}', [App\Http\Controllers\Backend\Notification\NotificationController::class, 'remove'])->name('notificationRemove');
        Route::get('/remove-selected', [App\Http\Controllers\Backend\Notification\NotificationController::class, 'remove_multiple'])->name('ajaxNotificationRemoveMulti');
        // remove notification

        // records
        Route::get('/', [App\Http\Controllers\Backend\Notification\NotificationController::class, 'records'])->name('notificationRecordsPage');
        // records
    });
    // notification

    // accounts
    Route::prefix('/accounts')->group(function() {

        // create admin account
        Route::get('/create', [App\Http\Controllers\Backend\Admin\Accounts\AccountsController::class, 'create'])->name('adminCreateAccount');
        Route::post('/create', [App\Http\Controllers\Backend\Admin\Accounts\AccountsController::class, 'create_post'])->name('adminCreateAccountUpdate');
        // create admin account

        // view admin account
        Route::get('/view/{admin_data}', [App\Http\Controllers\Backend\Admin\Accounts\AccountsController::class, 'view'])->name('adminViewAccount');
        // view admin account

        // edit admin account
        Route::get('/edit/{admin_data}', [App\Http\Controllers\Backend\Admin\Accounts\AccountsController::class, 'update'])->name('adminUpdateAccount');
        Route::post('/edit/{admin_data}', [App\Http\Controllers\Backend\Admin\Accounts\AccountsController::class, 'update_post'])->name('adminUpdateAccountPost');
        // edit admin account

        // remove admin account
        Route::get('/remove/{data}/history/{item}', [App\Http\Controllers\Backend\Admin\Accounts\AccountsController::class, 'remove_history_item'])->name('adminHistoryItemRemove');

        Route::get('/remove/{admin_data}', [App\Http\Controllers\Backend\Admin\Accounts\AccountsController::class, 'remove'])->name('adminRemoveAccount');
        Route::get('/remove-selected', [App\Http\Controllers\Backend\Admin\Accounts\AccountsController::class, 'remove_multiple'])->name('ajaxAdminRemoveMulti');
        // remove admin account

        // admins status
        Route::get('/status-change', [App\Http\Controllers\Backend\Admin\Accounts\AccountsController::class, 'status']);
        // admins status

        // trashed
        Route::get('/trashed', [App\Http\Controllers\Backend\Admin\Accounts\AccountsController::class, 'trashed_records'])->name('adminTrashRecordsPage');
        Route::get('/restore/{data}', [App\Http\Controllers\Backend\Admin\Accounts\AccountsController::class, 'trashed_record_restore'])->name('adminTrashRecordsRestore');
        // trashed

        // records
        Route::get('/', [App\Http\Controllers\Backend\Admin\Accounts\AccountsController::class, 'records'])->name('adminRecordsPage');
        // records
    });
    // accounts

    // authorization
    Route::prefix('/authorization')->group(function() {
        // permission
        Route::prefix('permissions')->group(function() {
            Route::get('/chaos', [App\Http\Controllers\Backend\Admin\Authorization\PermissionsController::class, 'chaos']);

            Route::get('/', [App\Http\Controllers\Backend\Admin\Authorization\PermissionsController::class, 'index'])->name('adminPermission');
            Route::post('/', [App\Http\Controllers\Backend\Admin\Authorization\PermissionsController::class, 'save'])->name('adminPermissionSave');

            Route::get('/edit/{data}', [App\Http\Controllers\Backend\Admin\Authorization\PermissionsController::class, 'edit'])->name('adminPermissionEdit');
            Route::post('/edit/{data}', [App\Http\Controllers\Backend\Admin\Authorization\PermissionsController::class, 'edit_post'])->name('adminPermissionEditSave');

            Route::get('/remove/{data}', [App\Http\Controllers\Backend\Admin\Authorization\PermissionsController::class, 'remove'])->name('adminRemovePermission');
            Route::get('/remove-selected', [App\Http\Controllers\Backend\Admin\Authorization\PermissionsController::class, 'remove_multiple'])->name('ajaxPermissionRemoveMulti');
        });
        // permission

        // roles
        Route::prefix('roles')->group(function() {
            Route::get('/', [App\Http\Controllers\Backend\Admin\Authorization\RolesController::class, 'index'])->name('adminRoles');

            Route::get('/create', [App\Http\Controllers\Backend\Admin\Authorization\RolesController::class, 'create'])->name('adminRolesCreate');
            Route::post('/create', [App\Http\Controllers\Backend\Admin\Authorization\RolesController::class, 'create_post'])->name('adminRolesCreateSave');

            Route::get('/view/{data}', [App\Http\Controllers\Backend\Admin\Authorization\RolesController::class, 'view'])->name('adminRolesView');

            Route::get('/edit/{data}', [App\Http\Controllers\Backend\Admin\Authorization\RolesController::class, 'edit'])->name('adminRolesEdit');
            Route::post('/edit/{data}', [App\Http\Controllers\Backend\Admin\Authorization\RolesController::class, 'edit_post'])->name('adminRolesEditSave');

            Route::get('/remove/{data}', [App\Http\Controllers\Backend\Admin\Authorization\RolesController::class, 'remove'])->name('adminRemoveRoles');
            Route::get('/remove-selected', [App\Http\Controllers\Backend\Admin\Authorization\RolesController::class, 'remove_multiple'])->name('ajaxRolesRemoveMulti');
        });
        // roles
    });
    // authorization
});
// system