<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AuthController, HomeController, OrderController, PointController, ReviewController, ProductController, ProfileController, RajaOngkirController, TransactionController, PaymentController, MenuCategoryController};
use App\Http\Controllers\Admin\DashboardController;

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

// Landing page
Route::get('/', function () {
    return view('landing.index', [
        "title" => "Landing",
    ]);
});

// Auth Routes
Route::middleware(['web'])->group(function () {
    Route::get('/auth', [AuthController::class, "loginGet"])->name("auth");
    Route::get('/auth/login', [AuthController::class, "loginGet"]);
    Route::post('/auth/login', [AuthController::class, "loginPost"])->name('auth.login');
    Route::get('/auth/register', [AuthController::class, "registrationGet"]);
    Route::post('/auth/register', [AuthController::class, "registrationPost"]);
});

// main
Route::middleware(['auth'])->group(function () {
    // Home
    Route::controller(HomeController::class)->group(function () {
        Route::get("/home", "index");
        Route::get("/home/customers", "customers");
    });

    // profile
    Route::controller(ProfileController::class)->group(function () {
        Route::get("/profile/my_profile", "myProfile")->name('profile.show');
        Route::get("/profile/edit_profile", "editProfileGet")->name('profile.edit');
        Route::post("/profile/edit_profile/{user:id}", "editProfilePost")->name('profile.update');
        Route::get("/profile/change_password", "changePasswordGet")->name('profile.password.edit');
        Route::post("/profile/change_password", "changePasswordPost")->name('profile.password.update');
    });

    // Product
    Route::controller(ProductController::class)->group(function () {
        Route::get("/product", "index");
        Route::get("/product/data/{id}", "getProductData");

        // admin only
        Route::get("/product/add_product", "addProductGet")->can("add_product", App\Models\Product::class);
        Route::post("/product/add_product", "addProductPost")->can("add_product", App\Models\Product::class);
        Route::get("/product/edit_product/{product:id}", "editProductGet")->can("edit_product", App\Models\Product::class);
        Route::post("/product/edit_product/{product:id}", "editProductPost")->can("edit_product", App\Models\Product::class);
        Route::delete('/product/delete_product/{id}', [ProductController::class, 'deleteProduct'])->name('product.delete');

        
    });

    // Order
    Route::prefix('order')->group(function () {
        // View & Create Order
        Route::get('/make_order/{product}', [OrderController::class, 'makeOrderGet'])->name('order.make');
        Route::post('/make_order/{product}', [OrderController::class, 'makeOrderPost'])->name('order.store');
        
        // Order List & History
        Route::get('/order_data', [OrderController::class, 'orderData'])->name('order.data');
        Route::get('/order_history', [OrderController::class, 'orderHistory'])->name('order.history');
        Route::get('/order_data/{status_id}', [OrderController::class, 'orderDataFilter'])->name('order.filter');
        
        // Order Management
        Route::post('/cancel_order/{order}', [OrderController::class, 'cancelOrder'])->name('order.cancel');
        Route::post('/reject_order/{order}/{product}', [OrderController::class, 'rejectOrder'])->name('order.reject');
        Route::post('/approve_order/{order}/{product}', [OrderController::class, 'approveOrder'])->name('order.approve');
        Route::post('/end_order/{order}/{product}', [OrderController::class, 'endOrder'])->name('order.end');
        
        // Payment Proof
        Route::get('/getProof/{order}', [OrderController::class, 'getProofOrder'])->name('order.proof');
        Route::post('/upload_proof/{order}', [OrderController::class, 'uploadProof'])->name('order.upload_proof');
        Route::get('/delete_proof/{order}', [OrderController::class, 'deleteProof'])->name('order.delete_proof');
        
        // Edit Order
        Route::get('/edit_order/{order}', [OrderController::class, 'editOrderGet'])->name('order.edit');
        Route::post('/edit_order/{order}', [OrderController::class, 'editOrderPost'])->name('order.update');
    });

    // Reviews
    Route::prefix('review')->middleware(['auth'])->group(function () {
        Route::get('/', [ReviewController::class, 'index'])->name('review.index');
        Route::get('/create/{order}', [ReviewController::class, 'create'])->name('review.create');
        Route::post('/store/{order}', [ReviewController::class, 'store'])->name('review.store');
        Route::get('/edit/{review}', [ReviewController::class, 'edit'])->name('review.edit');
        Route::put('/update/{review}', [ReviewController::class, 'update'])->name('review.update');
        Route::delete('/destroy/{review}', [ReviewController::class, 'destroy'])->name('review.destroy');
    });

    // Ongkir
    Route::controller(RajaOngkirController::class)->group(function () {
        Route::get("/shipping/province", "province");
        Route::get("/shipping/city/{province_id}", "city");
        Route::get("/shipping/cost/{origin}/{destination}/{quantity}/{courier}", "cost");
    });


    // review
    Route::controller(ReviewController::class)->group(function () {
        Route::get("/review/product/{product}", "productReview");
        Route::get("/review/data/{review}", "getDataReview");
        Route::post("/review/add_review/", "addReview");
        Route::post("/review/edit_review/{review}", "editReview")->can("edit_review", "review");
        Route::post("/review/delete_review/{review}", "deleteReview")->can("delete_review", "review");
    });

    // transaction
    Route::controller(TransactionController::class)->group(function () {
        Route::get("/transaction", "index")->can("is_admin");
        Route::get("/transaction/add_outcome", "addOutcomeGet")->can("is_admin");
        Route::post("/transaction/add_outcome", "addOutcomePost")->can("is_admin");
        Route::get("/transaction/edit_outcome/{transaction}", "editOutcomeGet")->can("is_admin");
        Route::post("/transaction/edit_outcome/{transaction}", "editOutcomePost")->can("is_admin");
    });

    // point
    Route::controller(PointController::class)->group(function () {
        Route::get("/point/user_point", "user_point")->can("user_point", App\Models\User::class);
        Route::post("/point/convert_point", "convert_point")->can("convert_point", App\Models\User::class);
    });

    // Payment Routes
    Route::get('/payment/process/{order}', [PaymentController::class, 'process'])->name('payment.process');
    Route::post('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');
    Route::get('/payment/finish', [PaymentController::class, 'finish'])->name('payment.finish');
    Route::get('/payment/pending', [PaymentController::class, 'pending'])->name('payment.pending');
    Route::get('/payment/error', [PaymentController::class, 'error'])->name('payment.error');

    // chart
    Route::middleware(['can:is_admin'])->group(function () {
        // sales chart
        Route::get("/chart/sales_chart", function () {
            $oneWeekAgo = DB::select(DB::raw('SELECT DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 6 DAY), "%Y-%m-%d") AS date'))[0]->date;

            $now = date('Y-m-d', time());

            $array_result = [
                "one_week_ago" => $oneWeekAgo,
                "now" => $now,
            ];

            //disable ONLY_FULL_GROUP_BY
            DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
            $array_result["data"] = DB::table("orders")
                ->selectSub("count(*)", "sales_total")
                ->selectSub("DATE_FORMAT(orders.updated_at, '%d')", "day")
                ->selectSub("DATE_FORMAT(orders.updated_at, '%Y-%m-%d')", "date")
                ->where("is_done", 1)
                ->whereBetween(DB::raw("DATE_FORMAT(orders.updated_at, '%Y-%m-%d')"), ["$oneWeekAgo", $now])
                ->groupByRaw("DATE_FORMAT(orders.updated_at, '%Y-%m-%d')")
                ->get();
            //re-enable ONLY_FULL_GROUP_BY
            DB::statement("SET sql_mode=(SELECT CONCAT(@@sql_mode, ',ONLY_FULL_GROUP_BY'));");

            echo json_encode($array_result);
        });
        // profits chart
        Route::get("/chart/profits_chart", function () {
            $six_month_ago = DB::select(DB::raw('SELECT DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 5 MONTH), "%Y-%m") AS month'))[0]->month;
            $now = date('Y-m', time());
            $array_result = [
                "six_month_ago" => $six_month_ago,
                "now" => $now,
            ];

            //disable ONLY_FULL_GROUP_BY
            DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
            $array_result["data"] = DB::table("transactions")
                ->selectSub("SUM(income) - SUM(outcome)", "profits")
                ->selectSub("DATE_FORMAT(transactions.created_at, '%Y-%m')", "date")
                ->whereBetween(DB::raw("DATE_FORMAT(transactions.created_at, '%Y-%m')"), ["$six_month_ago", $now])
                ->groupByRaw("DATE_FORMAT(transactions.created_at, '%Y-%m')")
                ->get();
            //re-enable ONLY_FULL_GROUP_BY
            DB::statement("SET sql_mode=(SELECT CONCAT(@@sql_mode, ',ONLY_FULL_GROUP_BY'));");

            echo json_encode($array_result);
        });
    });

    // Menu Categories (Admin Only)
    Route::middleware(['auth', 'can:manage_categories'])->group(function () {
        Route::resource('menu-categories', MenuCategoryController::class);
    });

    // Logout
    Route::post('/auth/logout', [AuthController::class, "logoutPost"])->name('logout');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/filter/{period}', [DashboardController::class, 'filter'])->name('dashboard.filter');
    // Products
    Route::resource('products', App\Http\Controllers\Admin\ProductController::class);
    Route::post('products/{product}/status', [App\Http\Controllers\Admin\ProductController::class, 'updateStatus'])->name('products.status');

    // Orders
    Route::get('orders', [App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/history', [App\Http\Controllers\Admin\OrderController::class, 'history'])->name('orders.history');
    Route::get('orders/{order}', [App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
    Route::post('orders/{order}/approve', [App\Http\Controllers\Admin\OrderController::class, 'approve'])->name('orders.approve');
    Route::post('orders/{order}/reject', [App\Http\Controllers\Admin\OrderController::class, 'reject'])->name('orders.reject');
    Route::post('orders/{order}/complete', [App\Http\Controllers\Admin\OrderController::class, 'complete'])->name('orders.complete');

    // Transactions
    Route::resource('transactions', App\Http\Controllers\Admin\TransactionController::class);

    // Categories
    Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class);

    // Users
    Route::resource('users', App\Http\Controllers\Admin\UserController::class);
    Route::post('users/{user}/update-point', [App\Http\Controllers\Admin\UserController::class, 'updatePoint'])->name('users.update-point');
    Route::post('users/{user}/update-coupon', [App\Http\Controllers\Admin\UserController::class, 'updateCoupon'])->name('users.update-coupon');
});

// transaction
Route::prefix('transaction')->middleware(['auth'])->group(function () {
    Route::get('/', [TransactionController::class, 'index'])->name('transaction.index');
    Route::post('/', [TransactionController::class, 'store'])->name('transaction.store');
    Route::get('/{transaction}/edit', [TransactionController::class, 'edit'])->name('transaction.edit');
    Route::put('/{transaction}', [TransactionController::class, 'update'])->name('transaction.update');
    Route::delete('/{transaction}', [TransactionController::class, 'destroy'])->name('transaction.destroy');
});
