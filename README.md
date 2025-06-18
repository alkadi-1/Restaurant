## Restaurant POS and Website

![Screenshot 2025-06-18 172203](https://github.com/user-attachments/assets/9d40045b-b143-4fb0-969a-d01425dd5ff4)

**Built with these:** 
<p align="left">
   <a href="#">
      <img alt="HTML5" src="https://img.shields.io/badge/html5%20-%23E34F26.svg?&style=for-the-badge&logo=html5&logoColor=white"/>
      <img alt="CSS3" src="https://img.shields.io/badge/css3%20-%231572B6.svg?&style=for-the-badge&logo=css3&logoColor=white"/>
      <img alt="MySQL" src="https://img.shields.io/badge/mysql-%2300f.svg?&style=for-the-badge&logo=mysql&logoColor=white"/>
      <img alt="Php" src="https://img.shields.io/badge/php-474a8a?style=for-the-badge&logo=php&logoColor=white" />
      <img alt="JavaScript" src="https://img.shields.io/badge/javascript%20-%23F7DF1E.svg?&style=for-the-badge&logo=javascript&logoColor=black"/>
   </a>
</p>

**Using:** Php 7.4

**Features:**
* **Customer Side (customerSide Folder):** Stores the website and allows customers to:
    * Make reservations
    * Register for accounts
    * View profile points
* **Staff Side (adminSide Folder):** Stores the panels and allows staff to:
    * Take orders
    * Send orders to the kitchen
    * Process payments
    * Print receipts
    * Manage CRUD operations
    * View user preferences
    * Download reports
    * View charts and graph



**Steps to run the project locally for Netbeans Manually:**

1. Open XAMPP, start Apache and MySQL.
2. Create a new project in Netbeans named `RestaurantProject`.
3. Under categories, select PHP, the PHP Application under Projects.
4. In Run Configuration, the "Run As" should be Local Web Site. (If your using Xampp).
5. Then Finish.
6. Delete the `setup_completed.flag` file in the RestaurantProject-main. (Extracted version)
7. Copy all the folders and files (adminSide, customerSide, index.php, and restaurantDB.txt) from the RestaurantProject-main into the `Source Files` directory.
8. Make sure there is no database named `restaurantdb`.
9. Run the project.

## Example accounts

| Role | Email | Password |
|---|---|---|
| Staff | 1 | password123 |
| Admin | 99999 | 12345 |

## Screenshots
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


## If you want to put a password for the database, change the config.php files.
