-- Nom data base : Salle Sport 

-- creation des tableaux
     -- Create Members table
CREATE TABLE Members (
    member_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL,
    phone_number VARCHAR(15) NOT NULL
);

-- Create Activities table
CREATE TABLE Activities (
    activity_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    description TEXT,
    capacity INT(11) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    disponibility TINYINT(1) DEFAULT 1
);

