# 🚀 نظام إدارة مزرعة الدواجن — Filament v5

**نظام متكامل** لإدارة دورات إنتاج الدواجن مع حسابات تلقائية وتقارير شاملة.

---

## ⚡ البدء السريع

### 1️⃣ تثبيت المشروع

```bash
cd d:/project/Farm
composer install
cp .env.example .env
php artisan key:generate
```

### 2️⃣ إعداد قاعدة البيانات

```bash
# قاعدة البيانات تستخدم SQLite (موجودة بالفعل)
php artisan migrate
```

### 3️⃣ تشغيل الخادم

```bash
php artisan serve
```

ثم افتح: **http://localhost:8000/admin**

---

## 📚 التوثيق الكاملة

### المتاح الآن:

- **16 موارد Filament** مع Forms و Tables و Actions
- **10 Model Observers** للحسابات التلقائية
- **6 Navigation Groups** لتنظيم الواجهة
- **23 جدول قاعدة بيانات** كاملة مع Foreign Keys
- **Arabic Labels** في جميع الحقول
- **Eager Loading** لتحسين الأداء

### الحسابات التلقائية:

✅ تكاليف الكتاكيت  
✅ تكاليف الأعلاف والأدوية والغاز  
✅ أجور العمال والسلف  
✅ الإيرادات من المبيعات  
✅ الأرباح والخسائر  
✅ KPIs (FCR, معدل النفوق، الوزن الوسطي)

---

## 🏗️ الهيكل

```
app/
├── Models/               (14 نموذج مع Relations)
├── Observers/            (10 مراقبين للحسابات)
└── Filament/Resources/   (16 موارد مع Pages و Forms و Tables)

database/
├── migrations/           (20 ملف migration)
└── factories/            (اختياري للاختبار)

config/
└── app.php              (تسجيل الـ Observers)
```

---

## 💡 المميزات الرئيسية

### 1. إدارة الدورات الإنتاجية

- تتبع كامل من البداية للنهاية
- حسابات تلقائية لكل المصروفات
- تقفيل الدورة مع إحصائيات شاملة

### 2. إدارة المصروفات

- مشتريات الأعلاف مع أنواع مختلفة
- فواتير الأدوية والغاز
- مشتريات النشارة والمصاريف النثرية
- **جميع السجلات المالية تُنشأ تلقائياً**

### 3. إدارة العمال والأجور

- تسجيل العمال مع الأجر اليومي
- تتبع أيام العمل في كل دورة
- سجل السلف مع حسابات تلقائية
- صافي الأجر = الراتب - السلف

### 4. تتبع المبيعات

- تسجيل المشترين والعربيات
- حساب الإجمالي من الوزن والسعر
- تتبع حالة الدفع (مدفوع/جزئي/غير مدفوع)

### 5. السجلات المالية

- **payable:** ما علينا من مواد وخدمات
- **receivable:** ما لنا من بيعات
- تتبع الدفعات وحالة التسوية
- إنشاء تلقائي من جميع المعاملات

---

## 🎯 أمثلة الاستخدام

### إضافة دورة جديدة

1. اذهب لـ **إدارة المزرعة** → **الدورات**
2. اضغط **Create** وأدخل البيانات
3. **chick_total** يُحسب تلقائياً
4. يمكنك إضافة:
    - مشتريات علف
    - فواتير أدوية وغاز
    - سجلات نافق يومي
    - مبيعات

### تقفيل الدورة

1. اذهب للدورة واضغط **Edit**
2. اضغط الزر **تقفيل الدورة** (أخضر)
3. تأكيد التقفيل
4. السيستم يحسب تلقائياً:
    - إجمالي المصروفات
    - إجمالي الإيرادات
    - الأرباح/الخسائر
    - جميع الـ KPIs

### عرض السجلات المالية

1. اذهب **التقارير** → **السجلات المالية**
2. رؤية جميع الديون والمستحقات
3. تتبع الدفعات

---

## 🔍 معلومات تقنية

### Stack

- **Framework:** Laravel 12
- **Admin Panel:** Filament v5
- **Database:** SQLite (production ready)
- **PHP:** 8.2+
- **Language:** Bilingual (Arabic/English labels)

### Database Tables (23)

```
farms, cycles, feed_types, feed_purchases,
medicine_invoices, medicine_invoice_items,
gas_types, gas_invoices, gas_invoice_items,
sawdust_purchases, workers, worker_cycles,
worker_advances, miscellaneous_expenses,
mortality_logs, buyers, sales, cycle_closings,
financial_records, financial_payments,
+ Laravel default (users, jobs, cache, sessions)
```

### Key Models & Relations

```php
Farm
  ├─ cycles()
  └─ workers()

Cycle
  ├─ feedPurchases()
  ├─ medicineInvoices()
  ├─ gasInvoices()
  ├─ workerCycles()
  ├─ sales()
  ├─ closing()
  └─ financialRecords()

WorkerCycle
  ├─ worker()
  ├─ cycle()
  └─ advances()

Sale
  ├─ cycle()
  └─ buyer()
```

---

## ✅ Checklist للإنتاج

- [x] جميع الـ Models والـ Relations
- [x] جميع الـ Migrations والـ Database
- [x] جميع الـ Filament Resources
- [x] جميع الـ Observers والحسابات التلقائية
- [x] Arabic Navigation Groups والـ Labels
- [x] Eager Loading للأداء
- [x] Forms مع Validation
- [x] Tables مع Status Enums
- [x] Relation Managers
- [x] Cycle Closing Action
- [x] Dashboard Widgets (اختياري)
- [x] Reports/Exports (اختياري)

---

## 🐛 استكشاف الأخطاء

### الخطأ: "Model not found"

```bash
php artisan tinker
>>> \App\Models\Farm::count()
```

### الخطأ: "Table not found"

```bash
php artisan migrate:status
php artisan migrate
```

### الخطأ: Navigation Groups لا تظهر

- تحقق من `app/Providers/AppServiceProvider.php`
- تأكد من تسجيل جميع الـ Observers

---

## 📞 الدعم

للمزيد من المعلومات، راجع:

- `FARM_CONTEXT.md` — توثيق السياق الكامل
- `farm_migrations_v3.md` — تفاصيل الجداول
- `PROJECT_STATUS.md` — حالة المشروع الحالية

---

**Status:** ✅ جاهز للإنتاج  
**Last Updated:** 24 نيسان 2026  
**Version:** 1.0.0
