# Hospital_management_system
The Hospital Management System is a web-based application developed using PHP and MySQL. It provides a comprehensive solution for managing various aspects of a hospital, including patient records, staff and admin user management, and ward and doctor information.

#Database info
Create an connect a database..

To create a MySQL database and design the tables for storing patient information using XAMPP, follow these steps:

Start XAMPP:
Open the XAMPP control panel and start the Apache and MySQL services.
Access phpMyAdmin:
Open a web browser and go to http://localhost/phpmyadmin.
phpMyAdmin is a web-based interface for managing MySQL databases.
Create a new database:
In the phpMyAdmin interface, click on the "Databases" tab.
Enter a name for your database (e.g., "hospital_db") in the "Create database" field.
Choose the appropriate collation (e.g., "utf8mb4_general_ci") and click the "Create" button.
Design the tables:
After creating the database, click on its name in the left-hand menu to select it.
Go to the "SQL" tab to execute SQL queries for creating tables.

Create the "patients" table:

CREATE TABLE patients (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100),
  contact_info VARCHAR(100),
  in_out_patient ENUM('in', 'out'),
  ward_id INT,
  doctor_id INT
);

Create the "wards" table:

CREATE TABLE wards (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100)
);

Create the "doctors" table:

CREATE TABLE doctors (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100),
  specialization VARCHAR(100)
);

Create the "users" table for authentication:

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50),
  password VARCHAR(255),
  role ENUM('staff', 'admin')
);

Click the "Go" button to execute each SQL query and create the respective tables.

Establish table relationships:

You can establish relationships between the tables using foreign keys. For example, to relate the "patients" table with the "wards" and "doctors" tables, you can add foreign key constraints:

ALTER TABLE patients
ADD FOREIGN KEY (ward_id) REFERENCES wards(id),
ADD FOREIGN KEY (doctor_id) REFERENCES doctors(id);
Execute the SQL query to add the foreign key constraints.
Insert sample data (optional):

You can insert sample data into the tables using SQL INSERT statements. For example:

INSERT INTO wards (name) VALUES ('General Ward'), ('ICU'), ('Pediatric Ward');
INSERT INTO doctors (name, specialization) VALUES ('Dr. John Doe', 'Cardiology'), ('Dr. Jane Smith', 'Pediatrics');
INSERT INTO patients (name, contact_info, in_out_patient, ward_id, doctor_id) VALUES ('Patient 1', '1234567890', 'in', 1, 1), ('Patient 2', '9876543210', 'out', NULL, 2);
INSERT INTO users (username, password, role) VALUES ('staff_user', 'password123', 'staff'), ('admin_user', 'admin123', 'admin');
Execute the SQL queries to insert the sample data into the respective tables.
That's it! You have now created a MySQL database and designed the tables for storing patient information using XAMPP and phpMyAdmin. You can proceed with building your PHP application to interact with this database and implement the required functionalities.
