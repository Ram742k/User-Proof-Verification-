# Laravel User & Admin Dashboard - Document Approval System

## 🚀 Project Overview

This is a Laravel-based system where users can upload **ID Proofs & Address Proofs** for approval.  

Admins can **review, approve, or reject** the uploaded documents.

---

## 🛠️ Setup Instructions

### **1️⃣ Clone the Repository**

```sh

git clone https://github.com/your-repository.git

cd your-repository


### **2️⃣ Install Dependencies**
`composer install
npm install`

3️⃣ Configure Environment
-   Update the following **Database credentials** inside `.env`:

`DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password`

### **4️⃣ Run Migrations & Seeders**

`php artisan migrate --seed`

### **5️⃣ Start the Server**

`php artisan serve`

The app will be available at **http://127.0.0.1:8000**


🧑‍💻 **Usage Instructions**
----------------------------

### **🔹 User Module**

-   **Register/Login** to access the dashboard.
-   Upload **ID Proof** and **Address Proof**.
-   Check document approval status.

### **🔹 Admin Module**

-   View a **list of users** with uploaded documents.
-   **Filter** users based on document approval status.
-   **Search** users by email.
-   Approve/Reject uploaded documents.

⚡ API Routes
------------

| Method | Route | Description |
| --- | --- | --- |
| GET | `/user` | List users with filtering/search |
| POST | `/upload-proof/{id}` | Upload proof documents |
| GET | `/admin` | Admin dashboard |
| POST | `/admin/update-status` | Approve/Reject user proofs |


🎯 Features
-----------

✅ **User Registration & Login**\
✅ **Document Upload (ID & Address Proofs)**\
✅ **Admin Dashboard for Approvals**\
✅ **Status Filtering & Email Search**\
✅ **Pagination for Users List**\
✅ **Ajax-based Filtering & Document Upload**


🛠️ Technologies Used
---------------------

-   **Laravel 10**
-   **Bootstrap**
-   **jQuery & AJAX**
-   **MySQL**
-   **Blade Templates**


