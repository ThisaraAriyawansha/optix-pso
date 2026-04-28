<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Coupons\CouponController;
use App\Http\Controllers\Customers\CustomerController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Expenses\ExpenseController;
use App\Http\Controllers\Installments\InstallmentController;
use App\Http\Controllers\Invoices\InvoiceController;
use App\Http\Controllers\Loyalty\LoyaltyController;
use App\Http\Controllers\POS\POSController;
use App\Http\Controllers\Products\ProductController;
use App\Http\Controllers\PurchaseOrders\PurchaseOrderController;
use App\Http\Controllers\Quotations\QuotationController;
use App\Http\Controllers\Repairs\RepairJobController;
use App\Http\Controllers\Repairs\RepairJobStatusController;
use App\Http\Controllers\Reports\ExpenseReportController;
use App\Http\Controllers\Reports\SalesReportController;
use App\Http\Controllers\Reports\StockReportController;
use App\Http\Controllers\Reports\TechnicianReportController;
use App\Http\Controllers\Categories\CategoryController;
use App\Http\Controllers\Settings\BranchController;
use App\Http\Controllers\Settings\SystemSettingController;
use App\Http\Controllers\Settings\UserController;
use App\Http\Controllers\Stock\StockController;
use App\Http\Controllers\Stock\StockMovementController;
use App\Http\Controllers\Suppliers\SupplierController;
use App\Http\Controllers\Transfers\BranchTransferController;
use Illuminate\Support\Facades\Route;

// ── Auth ──────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ── Authenticated App ─────────────────────────────────────
Route::middleware(['auth', 'set.branch'])->group(function () {

    Route::get('/', fn() => redirect()->route('dashboard'));
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // POS
    Route::get('/pos', [POSController::class, 'index'])->name('pos.index');
    Route::post('/pos/sale', [POSController::class, 'completeSale'])->name('pos.sale');

    // Quotations
    Route::resource('quotations', QuotationController::class);
    Route::post('quotations/{quotation}/convert', [QuotationController::class, 'convertToInvoice'])->name('quotations.convert');

    // Invoices
    Route::resource('invoices', InvoiceController::class)->only(['index', 'show', 'destroy']);
    Route::post('invoices/{invoice}/payment', [InvoiceController::class, 'recordPayment'])->name('invoices.payment');
    Route::post('invoices/{invoice}/refund', [InvoiceController::class, 'refund'])->name('invoices.refund');

    // Products & Categories
    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class)->except(['show']);

    // Stock
    Route::get('stock', [StockController::class, 'index'])->name('stock.index');
    Route::post('stock/adjust', [StockController::class, 'adjust'])->name('stock.adjust');
    Route::get('stock/movements', [StockMovementController::class, 'index'])->name('stock.movements');

    // Purchase Orders
    Route::resource('purchase-orders', PurchaseOrderController::class);
    Route::post('purchase-orders/{purchaseOrder}/receive', [PurchaseOrderController::class, 'receive'])->name('purchase-orders.receive');

    // Branch Transfers
    Route::resource('transfers', BranchTransferController::class);
    Route::post('transfers/{transfer}/approve', [BranchTransferController::class, 'approve'])->name('transfers.approve');
    Route::post('transfers/{transfer}/receive', [BranchTransferController::class, 'markReceived'])->name('transfers.receive');

    // Customers
    Route::resource('customers', CustomerController::class);

    // Installments
    Route::get('installments', [InstallmentController::class, 'index'])->name('installments.index');
    Route::get('installments/{installment}', [InstallmentController::class, 'show'])->name('installments.show');
    Route::post('installments/{installment}/pay', [InstallmentController::class, 'recordPayment'])->name('installments.pay');

    // Loyalty
    Route::get('loyalty', [LoyaltyController::class, 'index'])->name('loyalty.index');
    Route::post('loyalty/{customer}/adjust', [LoyaltyController::class, 'adjust'])->name('loyalty.adjust');

    // Coupons
    Route::resource('coupons', CouponController::class)->except(['show']);

    // Repairs
    Route::resource('repairs', RepairJobController::class);
    Route::post('repairs/{repair}/status', [RepairJobStatusController::class, 'update'])->name('repairs.status');
    Route::get('repairs/{repair}/invoice', [RepairJobController::class, 'generateInvoice'])->name('repairs.invoice');

    // Suppliers
    Route::resource('suppliers', SupplierController::class)->except(['show']);

    // Expenses
    Route::resource('expenses', ExpenseController::class)->except(['show', 'edit', 'update']);

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('sales', [SalesReportController::class, 'index'])->name('sales');
        Route::get('sales/export', [SalesReportController::class, 'export'])->name('sales.export');
        Route::get('stock', [StockReportController::class, 'index'])->name('stock');
        Route::get('stock/export', [StockReportController::class, 'export'])->name('stock.export');
        Route::get('technician', [TechnicianReportController::class, 'index'])->name('technician');
        Route::get('expenses', [ExpenseReportController::class, 'index'])->name('expenses');
    });

    // Settings (admin only)
    Route::middleware('role:admin')->prefix('settings')->name('settings.')->group(function () {
        Route::resource('branches', BranchController::class)->except(['show']);
        Route::resource('users', UserController::class)->except(['show']);
        Route::get('system', [SystemSettingController::class, 'index'])->name('system');
        Route::post('system', [SystemSettingController::class, 'update'])->name('system.update');
    });
});
