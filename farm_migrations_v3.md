<?php

// ════════════════════════════════════════════════════════════
// Farm Management System — Migrations v3
// ترتيب التنفيذ مهم — اتبعه بالظبط
// ════════════════════════════════════════════════════════════
//
// 01. php artisan make:migration create_farms_table
// 02. php artisan make:migration create_cycles_table
// 03. php artisan make:migration create_feed_types_table
// 04. php artisan make:migration create_feed_purchases_table
// 05. php artisan make:migration create_medicine_invoices_table
// 06. php artisan make:migration create_medicine_invoice_items_table
// 07. php artisan make:migration create_gas_types_table
// 08. php artisan make:migration create_gas_invoices_table
// 09. php artisan make:migration create_gas_invoice_items_table
// 10. php artisan make:migration create_sawdust_purchases_table
// 11. php artisan make:migration create_workers_table
// 12. php artisan make:migration create_worker_cycles_table
// 13. php artisan make:migration create_worker_advances_table
// 14. php artisan make:migration create_miscellaneous_expenses_table
// 15. php artisan make:migration create_mortality_logs_table
// 16. php artisan make:migration create_buyers_table
// 17. php artisan make:migration create_sales_table
// 18. php artisan make:migration create_cycle_closings_table
// 19. php artisan make:migration create_financial_records_table
// 20. php artisan make:migration create_financial_payments_table
// ════════════════════════════════════════════════════════════


// ══════════════════════════════════════════════════════
// 01 - farms
// ══════════════════════════════════════════════════════
Schema::create('farms', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('location')->nullable();
    $table->integer('capacity')->default(0);      // طاقة المزرعة
    $table->text('notes')->nullable();
    $table->timestamps();
});


// ══════════════════════════════════════════════════════
// 02 - cycles (الدورات)
// ══════════════════════════════════════════════════════
Schema::create('cycles', function (Blueprint $table) {
    $table->id();
    $table->foreignId('farm_id')->constrained()->onDelete('cascade');
    $table->string('name')->nullable();
    $table->date('start_date');
    $table->date('end_date')->nullable();             // null = شغالة
    $table->integer('initial_count');                 // عدد الكتاكيت
    $table->decimal('chick_price', 8, 2)->default(0);
    $table->decimal('chick_total', 10, 2)->default(0); // يتحسب تلقائي: initial_count * chick_price
    $table->enum('status', ['active', 'closed'])->default('active');
    $table->text('notes')->nullable();
    $table->timestamps();

    $table->index(['farm_id', 'status']);
    $table->index('start_date');
});


// ══════════════════════════════════════════════════════
// 03 - feed_types
// ══════════════════════════════════════════════════════
Schema::create('feed_types', function (Blueprint $table) {
    $table->id();
    $table->string('name');                           // استارتر / جروير / فنيشر
    $table->timestamps();
});


// ══════════════════════════════════════════════════════
// 04 - feed_purchases
// كل مرة شراء = سجل جديد (ممكن 9 مرات في الدورة)
// ══════════════════════════════════════════════════════
Schema::create('feed_purchases', function (Blueprint $table) {
    $table->id();
    $table->foreignId('cycle_id')->constrained()->onDelete('cascade');
    $table->foreignId('feed_type_id')->constrained('feed_types')->onDelete('restrict');
    $table->string('supplier_name')->nullable();
    $table->string('supplier_phone')->nullable();

    $table->enum('unit', ['ton', 'bag'])->default('ton'); // طن أو شكارة
    $table->decimal('quantity', 10, 3);
    $table->decimal('unit_price', 10, 2);
    $table->decimal('total', 10, 2);                  // يتحسب: quantity * unit_price

    $table->date('purchase_date');

    $table->decimal('paid_amount', 10, 2)->default(0);
    $table->decimal('remaining', 10, 2)->default(0);  // يتحسب: total - paid_amount
    $table->enum('payment_status', ['paid', 'partial', 'unpaid'])->default('unpaid');

    $table->text('notes')->nullable();
    $table->timestamps();

    $table->index(['cycle_id', 'feed_type_id']);
    $table->index('purchase_date');
});


// ══════════════════════════════════════════════════════
// 05 - medicine_invoices (فواتير الأدوية)
// كل رحلة صيدلية = فاتورة واحدة
// ══════════════════════════════════════════════════════
Schema::create('medicine_invoices', function (Blueprint $table) {
    $table->id();
    $table->foreignId('cycle_id')->constrained()->onDelete('cascade');
    $table->string('pharmacy_name')->nullable();
    $table->string('pharmacy_phone')->nullable();
    $table->date('invoice_date');
    $table->decimal('total', 10, 2)->default(0);      // يتحسب من البنود تلقائي

    $table->decimal('paid_amount', 10, 2)->default(0);
    $table->decimal('remaining', 10, 2)->default(0);
    $table->enum('payment_status', ['paid', 'partial', 'unpaid'])->default('unpaid');

    $table->text('notes')->nullable();
    $table->timestamps();

    $table->index(['cycle_id', 'invoice_date']);
});


// ══════════════════════════════════════════════════════
// 06 - medicine_invoice_items (بنود فاتورة الدواء)
// ══════════════════════════════════════════════════════
Schema::create('medicine_invoice_items', function (Blueprint $table) {
    $table->id();
    $table->foreignId('medicine_invoice_id')->constrained()->onDelete('cascade');
    $table->string('medicine_name');
    $table->string('unit')->default('علبة');
    $table->decimal('quantity', 8, 2);
    $table->decimal('unit_price', 10, 2);
    $table->decimal('total', 10, 2);                  // يتحسب: quantity * unit_price
    $table->timestamps();

    $table->index('medicine_invoice_id');
});


// ══════════════════════════════════════════════════════
// 07 - gas_types (أنواع الأنابيب)
// ══════════════════════════════════════════════════════
Schema::create('gas_types', function (Blueprint $table) {
    $table->id();
    $table->string('name');                           // صغيرة / كبيرة / وسط
    $table->decimal('default_price', 8, 2)->default(0);
    $table->timestamps();
});


// ══════════════════════════════════════════════════════
// 08 - gas_invoices (فواتير الغاز)
// كل طلبية = فاتورة
// ══════════════════════════════════════════════════════
Schema::create('gas_invoices', function (Blueprint $table) {
    $table->id();
    $table->foreignId('cycle_id')->constrained()->onDelete('cascade');
    $table->string('supplier_name')->nullable();
    $table->string('supplier_phone')->nullable();
    $table->date('invoice_date');
    $table->decimal('total', 10, 2)->default(0);      // يتحسب من البنود

    $table->decimal('paid_amount', 10, 2)->default(0);
    $table->decimal('remaining', 10, 2)->default(0);
    $table->enum('payment_status', ['paid', 'partial', 'unpaid'])->default('unpaid');

    $table->text('notes')->nullable();
    $table->timestamps();

    $table->index(['cycle_id', 'invoice_date']);
});


// ══════════════════════════════════════════════════════
// 09 - gas_invoice_items (بنود فاتورة الغاز)
// مثال: 10 صغيرة + 6 كبيرة
// ══════════════════════════════════════════════════════
Schema::create('gas_invoice_items', function (Blueprint $table) {
    $table->id();
    $table->foreignId('gas_invoice_id')->constrained()->onDelete('cascade');
    $table->foreignId('gas_type_id')->constrained()->onDelete('restrict');
    $table->integer('quantity');
    $table->decimal('unit_price', 10, 2);
    $table->decimal('total', 10, 2);                  // يتحسب: quantity * unit_price
    $table->timestamps();

    $table->index('gas_invoice_id');
});


// ══════════════════════════════════════════════════════
// 10 - sawdust_purchases (النشارة)
// بالطن — كذا مرة في الدورة
// ══════════════════════════════════════════════════════
Schema::create('sawdust_purchases', function (Blueprint $table) {
    $table->id();
    $table->foreignId('cycle_id')->constrained()->onDelete('cascade');
    $table->string('supplier_name')->nullable();
    $table->string('supplier_phone')->nullable();
    $table->decimal('quantity_tons', 8, 3);
    $table->decimal('price_per_ton', 10, 2);
    $table->decimal('total', 10, 2);                  // يتحسب

    $table->date('purchase_date');

    $table->decimal('paid_amount', 10, 2)->default(0);
    $table->decimal('remaining', 10, 2)->default(0);
    $table->enum('payment_status', ['paid', 'partial', 'unpaid'])->default('unpaid');

    $table->text('notes')->nullable();
    $table->timestamps();

    $table->index('cycle_id');
});


// ══════════════════════════════════════════════════════
// 11 - workers (العمال)
// مرتبط بالمزرعة — بياناته الأساسية هنا
// ══════════════════════════════════════════════════════
Schema::create('workers', function (Blueprint $table) {
    $table->id();
    $table->foreignId('farm_id')->constrained()->onDelete('cascade');
    $table->string('name');
    $table->string('phone')->nullable();
    $table->decimal('daily_wage', 8, 2)->default(0);  // الأجر اليومي الافتراضي
    $table->enum('status', ['active', 'inactive'])->default('active');
    $table->text('notes')->nullable();
    $table->timestamps();

    $table->index(['farm_id', 'status']);
});


// ══════════════════════════════════════════════════════
// 12 - worker_cycles (العامل في الدورة)
// ──────────────────────────────────────────────────────
// الفكرة:
//   العامل ممكن يجي قبل الدورة بأيام للتحضير
//   وبعد الدورة بأيام للتطهير
//   فبنسجل تاريخ بدايته وتاريخ نهايته هنا
//
// الحساب التلقائي وقت التقفيل:
//   actual_days = end_date - start_date
//   gross_wage  = actual_days * daily_wage
//   net_wage    = gross_wage - total_advances
// ══════════════════════════════════════════════════════
Schema::create('worker_cycles', function (Blueprint $table) {
    $table->id();
    $table->foreignId('cycle_id')->constrained()->onDelete('cascade');
    $table->foreignId('worker_id')->constrained()->onDelete('cascade');

    // تواريخ العمل الفعلي (ممكن تختلف عن تواريخ الدورة)
    $table->date('work_start_date');                  // تاريخ وصول العامل
    $table->date('work_end_date')->nullable();         // تاريخ انتهاء عمله

    // الأجر اليومي المتفق عليه في هذه الدورة (snapshot)
    $table->decimal('daily_wage', 8, 2);

    // هتتحسب تلقائي وقت التقفيل
    $table->integer('actual_days')->nullable();        // work_end - work_start
    $table->decimal('gross_wage', 10, 2)->nullable();  // actual_days * daily_wage
    $table->decimal('total_advances', 10, 2)->default(0); // إجمالي السلف (من worker_advances)
    $table->decimal('net_wage', 10, 2)->nullable();    // gross_wage - total_advances

    $table->enum('payment_status', ['paid', 'partial', 'unpaid'])->default('unpaid');
    $table->decimal('paid_amount', 10, 2)->default(0);
    $table->decimal('remaining', 10, 2)->nullable();   // net_wage - paid_amount

    $table->text('notes')->nullable();
    $table->timestamps();

    $table->unique(['cycle_id', 'worker_id']);
    $table->index('cycle_id');
});


// ══════════════════════════════════════════════════════
// 13 - worker_advances (سُلَف العمال)
// ──────────────────────────────────────────────────────
// العامل بيسحب فلوس طول الدورة
// كل سحبة = سجل هنا
// إجمالي السلف يتخصم تلقائي من الراتب وقت التقفيل
// ══════════════════════════════════════════════════════
Schema::create('worker_advances', function (Blueprint $table) {
    $table->id();
    $table->foreignId('worker_cycle_id')->constrained()->onDelete('cascade');
    $table->decimal('amount', 10, 2);                 // المبلغ المسحوب
    $table->date('advance_date');
    $table->string('reason')->nullable();             // سبب السلفة (اختياري)
    $table->text('notes')->nullable();
    $table->timestamps();

    $table->index(['worker_cycle_id', 'advance_date']);
});


// ══════════════════════════════════════════════════════
// 14 - miscellaneous_expenses (المصاريف النثرية)
// ══════════════════════════════════════════════════════
Schema::create('miscellaneous_expenses', function (Blueprint $table) {
    $table->id();
    $table->foreignId('cycle_id')->constrained()->onDelete('cascade');
    $table->string('description');
    $table->string('paid_to')->nullable();
    $table->string('phone')->nullable();
    $table->decimal('amount', 10, 2);
    $table->date('expense_date');
    $table->enum('payment_status', ['paid', 'unpaid'])->default('paid');
    $table->text('notes')->nullable();
    $table->timestamps();

    $table->index('cycle_id');
    $table->index('expense_date');
});


// ══════════════════════════════════════════════════════
// 15 - mortality_logs (النافق اليومي)
// ══════════════════════════════════════════════════════
Schema::create('mortality_logs', function (Blueprint $table) {
    $table->id();
    $table->foreignId('cycle_id')->constrained()->onDelete('cascade');
    $table->date('log_date');
    $table->integer('count');
    $table->text('reason')->nullable();
    $table->timestamps();

    $table->unique(['cycle_id', 'log_date']);
    $table->index(['cycle_id', 'log_date']);
});


// ══════════════════════════════════════════════════════
// 16 - buyers (المشترين)
// اسم + تليفون + رقم العربية — بعيد عن الدورة
// ══════════════════════════════════════════════════════
Schema::create('buyers', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('phone')->nullable();
    $table->string('truck_number')->nullable();       // رقم العربية
    $table->text('notes')->nullable();
    $table->timestamps();
});


// ══════════════════════════════════════════════════════
// 17 - sales (المبيعات)
// السيستم يحسب الإجمالي تلقائي
// ══════════════════════════════════════════════════════
Schema::create('sales', function (Blueprint $table) {
    $table->id();
    $table->foreignId('cycle_id')->constrained()->onDelete('cascade');
    $table->foreignId('buyer_id')->nullable()->constrained()->onDelete('set null');

    $table->date('sale_date');
    $table->string('truck_number')->nullable();
    $table->decimal('total_weight_kg', 10, 2);
    $table->integer('bird_count')->nullable();
    $table->decimal('price_per_kg', 8, 2);
    $table->decimal('total', 10, 2);                  // يتحسب: total_weight_kg * price_per_kg

    $table->decimal('paid_amount', 10, 2)->default(0);
    $table->decimal('remaining', 10, 2)->default(0);
    $table->enum('payment_status', ['paid', 'partial', 'unpaid'])->default('unpaid');

    $table->text('notes')->nullable();
    $table->timestamps();

    $table->index(['cycle_id', 'sale_date']);
    $table->index('buyer_id');
});


// ══════════════════════════════════════════════════════
// 18 - cycle_closings (تقفيل الدورة)
// ──────────────────────────────────────────────────────
// كل البيانات هنا تتحسب تلقائي من الجداول فوق
// صاحب المزرعة بيضغط "تقفيل" → السيستم يحسب كل حاجة
//
// الحسابات التلقائية:
//   chick_total   = cycles.initial_count * cycles.chick_price
//   feed_cost     = SUM(feed_purchases.total)
//   feed_tons     = SUM(feed_purchases.quantity) WHERE unit = 'ton'
//   medicine_cost = SUM(medicine_invoices.total)
//   gas_cost      = SUM(gas_invoices.total)
//   sawdust_cost  = SUM(sawdust_purchases.total)
//   sawdust_tons  = SUM(sawdust_purchases.quantity_tons)
//   worker_cost   = SUM(worker_cycles.net_wage)
//   misc_cost     = SUM(miscellaneous_expenses.amount)
//   total_expenses= مجموع كل ده
//   total_revenue = SUM(sales.total)
//   net_profit    = total_revenue - total_expenses
//   mortality_count = SUM(mortality_logs.count)
//   sold_count    = SUM(sales.bird_count)
//   cycle_days    = DATEDIFF(end_date, start_date)
//   fcr           = (feed_tons * 1000) / total_weight_kg
//   avg_weight_kg = total_weight_kg / sold_count
//   mortality_rate= (mortality_count / initial_count) * 100
// ══════════════════════════════════════════════════════
Schema::create('cycle_closings', function (Blueprint $table) {
    $table->id();
    $table->foreignId('cycle_id')->unique()->constrained()->onDelete('cascade');
    $table->date('closing_date');

    // ── إحصائيات الطيور
    $table->integer('initial_count');
    $table->integer('mortality_count');
    $table->integer('sold_count');
    $table->integer('cycle_days');

    // ── تفاصيل المصاريف (كلها auto من الجداول)
    $table->decimal('chick_cost',      10, 2)->default(0);
    $table->decimal('feed_cost',       10, 2)->default(0);
    $table->decimal('feed_tons',       10, 3)->default(0);
    $table->decimal('medicine_cost',   10, 2)->default(0);
    $table->decimal('gas_cost',        10, 2)->default(0);
    $table->decimal('sawdust_cost',    10, 2)->default(0);
    $table->decimal('sawdust_tons',    10, 3)->default(0);
    $table->decimal('worker_cost',     10, 2)->default(0);
    $table->decimal('misc_cost',       10, 2)->default(0);
    $table->decimal('total_expenses',  10, 2)->default(0);

    // ── الإيرادات
    $table->decimal('total_revenue',   10, 2)->default(0);
    $table->decimal('total_weight_kg', 10, 2)->default(0);

    // ── الصافي
    $table->decimal('net_profit', 10, 2)->default(0);
    $table->enum('result', ['profit', 'loss', 'break_even'])->default('break_even');

    // ── KPIs
    $table->decimal('fcr', 6, 3)->nullable();
    $table->decimal('avg_weight_kg', 6, 3)->nullable();
    $table->decimal('mortality_rate', 5, 2)->nullable();
    $table->decimal('profit_per_kg', 8, 2)->nullable();

    $table->text('notes')->nullable();
    $table->timestamps();
});


// ══════════════════════════════════════════════════════
// 19 - financial_records (الديون والمستحقات)
// ──────────────────────────────────────────────────────
// تتعمل تلقائي من السيستم — مش يدوياً
//
// متى يعملها السيستم تلقائي؟
//   feed_purchase    → remaining > 0 → payable  (علينا للمورد)
//   medicine_invoice → remaining > 0 → payable  (علينا للصيدلية)
//   gas_invoice      → remaining > 0 → payable  (علينا لبتاع الغاز)
//   sawdust_purchase → remaining > 0 → payable  (علينا لبتاع النشارة)
//   worker_cycles    → remaining > 0 → payable  (علينا للعامل)
//   sales            → remaining > 0 → receivable (لينا من المشتري)
//
// لما صاحب المزرعة يسدد:
//   paid_amount += الدفعة
//   remaining   -= الدفعة
//   status = 'settled' لو remaining = 0
// ══════════════════════════════════════════════════════
Schema::create('financial_records', function (Blueprint $table) {
    $table->id();
    $table->foreignId('cycle_id')->nullable()->constrained()->onDelete('set null');

    $table->string('party_name');
    $table->string('party_phone')->nullable();
    $table->string('party_type')->nullable();         // مورد علف / صيدلية / مشتري / عامل

    $table->enum('type', ['payable', 'receivable']);
    // payable    = علينا — لازم ندفع
    // receivable = لينا  — المفروض يدفعوا

    $table->decimal('original_amount', 10, 2);
    $table->decimal('paid_amount', 10, 2)->default(0);
    $table->decimal('remaining', 10, 2);

    $table->date('due_date')->nullable();
    $table->enum('status', ['active', 'settled'])->default('active');

    // مرجع المصدر — عشان السيستم يعرف جه منين
    $table->string('source_type')->nullable();
    // feed_purchase / medicine_invoice / gas_invoice
    // sawdust_purchase / worker_cycle / sale
    $table->unsignedBigInteger('source_id')->nullable();

    $table->text('notes')->nullable();
    $table->timestamps();

    $table->index(['type', 'status']);
    $table->index('cycle_id');
    $table->index('due_date');
    $table->index(['source_type', 'source_id']);
});


// ══════════════════════════════════════════════════════
// 20 - financial_payments (سجل الدفعات)
// ──────────────────────────────────────────────────────
// كل دفعة على أي دين أو مستحق = سجل هنا
// بعد كل دفعة السيستم يحدث financial_records تلقائي
// ══════════════════════════════════════════════════════
Schema::create('financial_payments', function (Blueprint $table) {
    $table->id();
    $table->foreignId('financial_record_id')->constrained()->onDelete('cascade');
    $table->decimal('amount', 10, 2);
    $table->date('payment_date');
    $table->string('payment_method')->nullable();     // كاش / تحويل
    $table->text('notes')->nullable();
    $table->timestamps();

    $table->index(['financial_record_id', 'payment_date']);
});
