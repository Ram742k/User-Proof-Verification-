# User Proof Verification

## 🚀 Project Overview
This Laravel-based application allows users to upload **ID Proofs & Address Proofs** for verification. Admins can **approve, reject, or request reuploads** of submitted documents dynamically using AJAX.

---
## 🛠️ Setup Instructions

### 1️⃣ Clone the Repository
```sh
git clone https://github.com/Ram742k/User-Proof-Verification-.git
cd your-repository
```

### 2️⃣ Install Dependencies
```sh
composer install
npm install
```

### 3️⃣ Configure Environment
Update the `.env` file with your **database credentials**:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
```

### 4️⃣ Run Migrations & Seeders
```sh
php artisan migrate --seed
```

### 5️⃣ Start the Server
```sh
php artisan serve
```
The app will be available at **http://127.0.0.1:8000**

---
## 🧑‍💻 Usage Instructions

### 🔹 User Module
- Register/Login to access the dashboard.
- Upload **ID Proof** and **Address Proof**.
- Check the document approval status.

### 🔹 Admin Module
- View a **list of users** with uploaded documents.
- **Filter** users based on document approval status.
- **Search** users by email.
- Approve/Reject uploaded documents.
- Request users to reupload rejected proofs.

---
## ⚡ API Routes

| Method | Route | Description |
| --- | --- | --- |
| GET | `/user` | List users with filtering/search |
| POST | `/upload-proof/{id}` | Upload proof documents |
| GET | `/admin` | Admin dashboard |
| POST | `/admin/update-status` | Approve/Reject user proofs |

---
## 🎯 Features
✅ **User Registration & Login**  
✅ **Document Upload (ID & Address Proofs)**  
✅ **Admin Dashboard for Approvals**  
✅ **Status Filtering & Email Search**  
✅ **Pagination for Users List**  
✅ **AJAX-based Filtering & Document Upload**  
✅ **Role-Based Access Control**  
✅ **File Upload Validation & Security**  

---
## 🛠️ Technologies Used
- **Laravel 10**
- **Bootstrap**
- **jQuery & AJAX**
- **MySQL**
- **Blade Templates**

---
## 📜 Task Requirements Implementation

### **User List Page**
- ✅ Displays a **paginated** list of users.
- ✅ Columns: **Name, Email, Status, Created Time, Actions.**
- ✅ Status categorization:
  - **Not Submitted**: Both ID & Address Proof not uploaded.
  - **Waiting for Approval**: One or both proofs uploaded, pending approval.
  - **Approved**: Both ID & Address Proof approved.
  - **Rejected**: One or both proofs rejected.
- ✅ **Filters for Proof Status & Email Search**
- ✅ **Sorts "Waiting for Approval" users at the top.**

### **Admin Actions**
- ✅ Approve/Reject **ID Proof & Address Proof** separately.
- ✅ **Dynamic AJAX updates** for proof status without page reload.
- ✅ Allows users to **reupload rejected proofs**, changing status to **"Waiting for Approval"**.

### **Technical Requirements**
- ✅ **Database Integration** using Laravel Eloquent ORM.
- ✅ Optimized SQL queries for:
  - Fetching & sorting users
  - Updating proof statuses
- ✅ **AJAX for dynamic updates**:
  - Proof approval/rejection
  - Status updates without refresh
- ✅ **Secure File Upload Handling**:
  - Accepts **only .jpg, .png, .pdf** files.
  - Prevents malicious file uploads.
- ✅ **Pagination & Role-based Access Control**


---
## 🎯 Bonus Implementations
✅ **Role-based access control** (Only admins can approve/reject).  
✅ **AJAX-based real-time updates** without page reload.  
✅ **Optimized queries** for handling large user datasets.  
✅ **CSRF protection for AJAX requests & Forms.**  


---
## 🎉 Final Notes
This project is designed with **scalability, security, and usability** in mind. The combination of **Laravel, AJAX, and optimized queries** ensures a smooth **User Proof Verification System**. 🚀

 ## Additional Enhancements:
✅ Security Measures: Mention CSRF protection, validation for file uploads, and role-based access control.

✅ Error Handling: Describe how errors (like invalid file formats) are managed and displayed.

✅ Technology Stack: List versions of Laravel, MySQL, Bootstrap, jQuery, etc.

✅ Deployment Guide: Steps for hosting on a live server (e.g., Apache, Nginx).

✅ Testing Guide: Instructions on running test cases (if applicable).

✅ API Examples: Sample request/response JSON for AJAX-based actions.