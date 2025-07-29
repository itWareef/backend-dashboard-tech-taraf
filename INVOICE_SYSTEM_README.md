# نظام الفواتير - Invoice System

## نظرة عامة
تم إنشاء نظام فواتير متكامل مع علاقات مورفية (Polymorphic Relationships) مع جميع أنواع الطلبات في النظام.

## الميزات المطلوبة المنجزة

### 1. جدول الفواتير
- **العلاقات المورفية**: مع جميع أنواع الطلبات (MaintenanceRequest, PlantingRequest, ServiceRequest, GardenRequest, ContractRequest, UnitRequest, Order)
- **معرف العميل**: customer_id
- **قيمة الفاتورة**: amount
- **الحالات**: paid, unpaid
- **الوصف**: اختياري
- **رقم الفاتورة**: تلقائي

### 2. العلاقات المورفية
تم إضافة العلاقات المورفية إلى النماذج التالية:
- `MaintenanceRequest`
- `PlantingRequest`
- `ServiceRequest`
- `GardenRequest`
- `ContractRequest`
- `UnitRequest`
- `Order`

### 3. الخدمات (Services)
#### InvoiceService
- `createInvoice()`: إنشاء فاتورة جديدة
- `markAsPaid()`: تحديث حالة الفاتورة إلى مدفوع
- `markAsUnpaid()`: تحديث حالة الفاتورة إلى غير مدفوع
- `getCustomerInvoicesSummary()`: الحصول على ملخص فواتير العميل (مدفوع وغير مدفوع)
- `getCustomerInvoices()`: الحصول على جميع فواتير العميل
- `createInvoiceForOrder()`: إنشاء فاتورة تلقائياً عند إنشاء طلب
- `updateInvoiceStatusOnPayment()`: تحديث حالة الفاتورة عند اكتمال الدفع

### 4. API Endpoints

#### للعملاء (Customers)
```
GET /api/customer/invoices/summary
- إرجاع ملخص الفواتير (مدفوع وغير مدفوع)

GET /api/customer/invoices
- إرجاع جميع فواتير العميل
- يمكن تصفية حسب الحالة: ?status=paid أو ?status=unpaid

GET /api/customer/invoices/paid
- إرجاع جميع الفواتير المدفوعة للعميل

GET /api/customer/invoices/unpaid
- إرجاع جميع الفواتير غير المدفوعة للعميل

GET /api/customer/invoices/{id}
- إرجاع تفاصيل فاتورة محددة
```

#### للمديرين (Admins)
```
GET /api/admins/invoices
- إرجاع جميع الفواتير مع إمكانية التصفية

POST /api/admins/invoices
- إنشاء فاتورة جديدة

GET /api/admins/invoices/{id}
- إرجاع تفاصيل فاتورة محددة

PATCH /api/admins/invoices/{id}/status
- تحديث حالة الفاتورة

DELETE /api/admins/invoices/{id}
- حذف فاتورة
```

### 5. الأحداث والمراقبون (Events & Listeners)
- **Event**: `OrderCreated`
- **Listener**: `CreateInvoiceForOrder`
- **الوظيفة**: إنشاء فاتورة تلقائياً عند إنشاء طلب جديد

### 6. التحديث التلقائي لحالة الفاتورة
- عند اكتمال عملية الدفع، يتم تحديث حالة الفاتورة تلقائياً إلى "paid"
- يتم ربط نظام الدفع مع نظام الفواتير

## كيفية الاستخدام

### إنشاء فاتورة يدوياً
```php
use App\Services\InvoiceServices\InvoiceService;

$invoiceService = new InvoiceService();
$invoice = $invoiceService->createInvoice($model, 100.00, 'وصف الفاتورة');
```

### الحصول على ملخص فواتير العميل
```php
$summary = $invoiceService->getCustomerInvoicesSummary($customerId);
// Returns: ['paid' => 500.00, 'unpaid' => 200.00]
```

### تحديث حالة الفاتورة
```php
$invoiceService->markAsPaid($invoice);
// أو
$invoiceService->markAsUnpaid($invoice);
```

## قاعدة البيانات

### جدول invoices
```sql
CREATE TABLE invoices (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    customer_id BIGINT UNSIGNED NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    status ENUM('paid', 'unpaid') DEFAULT 'unpaid',
    description TEXT NULL,
    number VARCHAR(255) UNIQUE NOT NULL,
    invoiceable_type VARCHAR(255) NOT NULL,
    invoiceable_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE
);
```

## الملفات المضافة/المعدلة

### ملفات جديدة
- `app/Models/Invoice.php`
- `app/Services/InvoiceServices/InvoiceService.php`
- `app/Http/Requests/InvoiceRequest.php`
- `app/Http/Controllers/Api/InvoiceController.php`
- `app/Events/OrderCreated.php`
- `app/Listeners/CreateInvoiceForOrder.php`
- `database/migrations/2025_07_29_082351_create_invoices_table.php`

### ملفات معدلة
- `app/Models/Requests/MaintenanceRequest.php`
- `app/Models/Requests/PlantingRequest.php`
- `app/Models/RequestMaintenanceAndService/ServiceRequest.php`
- `app/Models/RequestMaintenanceAndService/GardenRequest.php`
- `app/Models/RequestMaintenanceAndService/ContractRequest.php`
- `app/Models/RequestMaintenanceAndService/UnitRequest.php`
- `app/Models/Store/Order.php`
- `app/Services/PaymentMoyasarServices.php`
- `app/Providers/AppServiceProvider.php`
- `routes/api.php`

## ملاحظات مهمة
1. يتم إنشاء رقم الفاتورة تلقائياً باستخدام `NumberingService`
2. عند إنشاء طلب جديد، يتم إنشاء فاتورة تلقائياً
3. عند اكتمال عملية الدفع، يتم تحديث حالة الفاتورة تلقائياً
4. جميع العلاقات محمية من الحذف المتسلسل
5. يتم استخدام transactions لضمان سلامة البيانات 