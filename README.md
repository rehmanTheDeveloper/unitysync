# UnitySync – Property Management System

**UnitySync** is a robust Core PHP + MySQL web application tailored for managing real estate operations. It helps sellers, buyers, and real estate firms track properties, handle transactions, manage payments, and generate comprehensive reports — all from a secure and centralized dashboard.

---

## 🔍 Overview

UnitySync is built for real-world property management. From managing users and roles to tracking sales, payments, returns, and bank transactions, the system provides a seamless experience with role-based access and real-time activity logs. It supports PDF receipt generation and uses AJAX for a smooth, dynamic interface.

---

## 🧩 Core Features

### 👥 Account Management
- Add/edit/view sellers, buyers, and users
- Manage roles and permissions
- Profile image uploads and activity logs

### 🏘️ Property & Project Handling
- Add and manage properties and projects
- Link sellers, buyers, and investors to projects
- Edit project details in real time

### 💳 Transactions & Payments
- Handle **received**, **paid**, and **transferred** payments
- Track outstanding balances
- Export ledgers with **PDF receipts**

### 📈 Reports & Ledger
- Sales ledger, purchase ledger, outstanding reports
- Return handling and print-ready reports
- Analytics via ApexCharts

### 🔐 License & Authentication System
- Super admin license validation
- User login/logout & session handling
- Activity log tracking

### 📦 PDF Export & Receipts
- Generate receipts via DOMPDF
- Downloadable and printable versions for compliance

---

## ⚙️ Tech Stack

### 💻 Backend
- Core PHP (no framework)
- MySQL

### 🌐 Frontend
- Bootstrap
- jQuery + AJAX

### 📦 Vendor Libraries
- **ApexCharts** – Visual reports
- **Dropzone / CropperJS** – File uploads & cropping
- **FullCalendar / Leaflet** – Advanced UI maps and calendar
- **SweetAlert2 / Notyf** – User notifications
- **DOMPDF** – PDF generation
- **Simple-Datatables / Choices.js** – Enhanced UI tables and selects

---

## 🗂️ Directory Structure

Here’s a simplified breakdown of important folders:

```
.
├── ajax/           # AJAX endpoints for dynamic data operations
├── auth/           # Authentication, license, user session
├── comp/           # Core business logic – adding/removing/updating data
├── temp/           # UI layout templates: nav, head, aside, scripts
├── uploads/        # Profile images, documents, cached images
├── assets/         # CSS/JS files, icons, logos
├── database/       # SQL schema for MySQL
├── license/        # License key files
├── object/         # OOP ledger logic
├── vendor/         # All frontend vendor libraries
└── .php files      # Pages for views (account, sale, property, dashboard, etc.)
```

---

## 🚀 Getting Started

1. **Clone the Repository**
   ```bash
   git clone https://github.com/your-username/unitysync.git
   ```

2. **Setup Database**  
   Import `database/unitysync.sql` into your MySQL DB

3. **Update Configuration**  
   Set database credentials in `/auth/DB.handler.php`

4. **Run the App**  
   Host the project using **XAMPP**, **Laragon**, or any Apache + PHP stack  
   Access via: `http://localhost/unitysync`

---

## 🧪 Pages & Functional Modules

| Page/File        | Description                                      |
|------------------|--------------------------------------------------|
| `dashboard.php`  | Main summary and charts                          |
| `account.*.php`  | Buyer/Seller management                          |
| `property.*.php` | Property listing and editing                     |
| `sale.*.php`     | Sale recording and receipt printing              |
| `ledger.*.php`   | Purchase/Sale ledgers                            |
| `payment.*.php`  | Payment (paid, received, transferred) tracking   |
| `return.*.php`   | Return recording and PDF printing                |
| `settings.php`   | License and app configuration                    |
| `user.*.php`     | User and role handling                           |
| `activity.php`   | System activity logs                             |
| `others.php`     | Miscellaneous features                           |

---

## 🔮 Future Improvements

- REST API for mobile or frontend frameworks  
- Multi-language support  
- Excel/CSV data export  
- Automated backup & audit logs  
- Role-based access matrix  

---

## 🙋 Author

**Abdul Rehman**  
Full Stack Developer  
🌐 [LinkedIn](https://www.linkedin.com/in/rehmanthedeveloper)  
💼 [Portfolio](https://me.infinitibytech.com)
