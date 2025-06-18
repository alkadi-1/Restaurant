## 🍽️ Restaurant POS and Website

![Screenshot](https://github.com/user-attachments/assets/9d40045b-b143-4fb0-969a-d01425dd5ff4)

A comprehensive restaurant management system designed for **Johnny’s Dining & Bar**, combining a dynamic customer-facing website with a powerful staff-side point-of-sale (POS) panel.

---

### 🔧 Built With

<p align="left">
   <img alt="HTML5" src="https://img.shields.io/badge/html5-%23E34F26.svg?&style=for-the-badge&logo=html5&logoColor=white"/>
   <img alt="CSS3" src="https://img.shields.io/badge/css3-%231572B6.svg?&style=for-the-badge&logo=css3&logoColor=white"/>
   <img alt="JavaScript" src="https://img.shields.io/badge/javascript-%23F7DF1E.svg?&style=for-the-badge&logo=javascript&logoColor=black"/>
   <img alt="PHP" src="https://img.shields.io/badge/php-474a8a?style=for-the-badge&logo=php&logoColor=white"/>
   <img alt="MySQL" src="https://img.shields.io/badge/mysql-%2300f.svg?&style=for-the-badge&logo=mysql&logoColor=white"/>
</p>

> **PHP Version:** 7.4+

---

### ✅ Features

#### 👤 Customer Side (`customerSide/`)
- Make table reservations
- Register and manage user accounts
- View and collect loyalty points
- Browse full menu with categories and dish images

#### 🧑‍🍳 Staff/Admin Side (`adminSide/`)
- Manage table status and take orders
- Process payments and print receipts
- Track kitchen orders
- Generate and download sales reports
- View graphical analytics
- Perform CRUD operations for menu, staff, and users

---

### 🛠️ Fixed Issues (Maintenance Summary)

As part of a software maintenance task, several key enhancements and fixes were applied:

- **🔐 Secure Password Storage:**  
  Replaced insecure plaintext storage with secure PHP `password_hash()` and `password_verify()` methods.

- **🏠 Broken Home Link Removed:**  
  Fixed the navigation bar by removing a non-functional Home button and linking the logo to the homepage.

- **🎨 Modernized Admin Interface:**  
  Updated all admin modules with a unified and responsive design using Bootstrap cards, tables, and modals.

- **🍽️ Redesigned Menu Layout:**  
  Migrated the customer menu to a dedicated page with a clean, card-based layout, category filters, and dish images.

- **💬 Improved Feedback Mechanism:**  
  Replaced outdated banner messages with accessible, styled inline notifications that auto-dismiss.

---

### 🧪 Example Accounts

| Role  | Username/Email | Password     |
|-------|----------------|--------------|
| Staff | `1`            | `password123`|
| Admin | `99999`        | `12345`      |

---

### 🖥️ How to Run Locally with NetBeans

1. Open **XAMPP** and start **Apache** & **MySQL**.
2. Create a new PHP project in NetBeans (e.g., `RestaurantProject`).
3. Set **Run As** to *Local Web Site* under the configuration settings.
4. Delete the `setup_completed.flag` file from the extracted folder.
5. Copy the contents of `RestaurantProject-main` into the `Source Files` folder.
6. Import `restaurantdb.txt` into MySQL or manually create the database schema.
7. Run the project in your browser via NetBeans or `http://localhost/RestaurantProject`.

> ⚠️ **Note:** Ensure there's **no existing database named `restaurantdb`** before starting.

---

### 📸 Screenshots
![Screenshot 2025-06-18 172521](https://github.com/user-attachments/assets/a1f52a80-6871-4d30-8980-ae844c7daa89)
![image](https://github.com/user-attachments/assets/444eca73-f10b-4e66-b88e-dc8bf50bdb31)
![image](https://github.com/user-attachments/assets/962472d2-38b9-4bbb-a57d-12b9444feb95)
![image](https://github.com/user-attachments/assets/163caa54-92e4-42da-ba63-8e43dc465e7e)
![image](https://github.com/user-attachments/assets/9e1045ee-2f1f-444c-8250-c49ad2cf8fbc)
![image](https://github.com/user-attachments/assets/84a443f1-5165-4dc1-b35e-a46045477c08)
![image](https://github.com/user-attachments/assets/dfebf0d5-65d9-4083-9f38-167d8cf64e42)
![image](https://github.com/user-attachments/assets/8460f2a1-54f0-4c6d-848e-0a5f29f96a2d)
![image](https://github.com/user-attachments/assets/7627191c-c957-4bea-8188-032d7b68abbf)
![image](https://github.com/user-attachments/assets/17a9646d-e8be-444a-9e5a-51a628b83abf)
![image](https://github.com/user-attachments/assets/5cccd128-44e0-41d4-aa9f-af427838b5c2)
![image](https://github.com/user-attachments/assets/87a99b9f-260c-44e1-9ae5-1bafa52698e6)
![image](https://github.com/user-attachments/assets/507ca403-820d-45af-8d4c-4eac2e12b2e7)
![image](https://github.com/user-attachments/assets/570c9a8c-7019-4253-bb9c-3349e6ab4833)
![image](https://github.com/user-attachments/assets/1e484e6a-0e62-43a5-9da1-1398d34a042e)
![image](https://github.com/user-attachments/assets/cdbb8c5f-5a53-4ae4-b4cb-315dc74605c2)
![image](https://github.com/user-attachments/assets/f23d59bd-e01d-4be3-847c-134e5535debe)
![image](https://github.com/user-attachments/assets/d47f7235-d029-4697-ba43-113f950415ba)
![image](https://github.com/user-attachments/assets/9de95084-f42b-4049-95e5-7e96a1e37ee0)
![image](https://github.com/user-attachments/assets/2db0776e-2be5-4528-bc9a-2fc79a2a9846)
![image](https://github.com/user-attachments/assets/2c5f4c0c-f193-4dfb-8a78-0ce8df97f69b)
![image](https://github.com/user-attachments/assets/b12ce40f-2db6-437d-a7b6-c816ab52962f)
![image](https://github.com/user-attachments/assets/8019fbe5-aae2-49ef-88d5-1c9f7746d440)

---

### 🔐 Database Credentials

If you wish to apply a password for your local MySQL database connection, make sure to **edit the following files**:

- `adminSide/inc/config.php`
- `customerSide/inc/config.php`

Update the `$password` variable with your desired DB password.
