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

-- Create Reservations table
CREATE TABLE Reservations (
    reservation_id INT PRIMARY KEY AUTO_INCREMENT,
    member_id INT NOT NULL,
    activity_id INT NOT NULL,
    reservation_date DATETIME NOT NULL,
    status ENUM('Confirmed', 'Cancelled') DEFAULT 'Confirmed',
    FOREIGN KEY (member_id) REFERENCES Members(member_id),
    FOREIGN KEY (activity_id) REFERENCES Activities(activity_id)
);


-- Create Tableau_activite table
CREATE TABLE Tableau_activite (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom_complet_membre VARCHAR(100) NOT NULL,
    Admit_activite ENUM('oui', 'Non') DEFAULT 'Non',
    nomComplet_coach VARCHAR(100),
    Supprimer ENUM('0','1') DEFAULT '0',
    activity_id INT,
    FOREIGN KEY (activity_id) REFERENCES Activities(activity_id)
);

-- Create Tableau_Authentifier table
CREATE TABLE Tableau_Authentifier (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom_complet_membre VARCHAR(50) NOT NULL,
    Post ENUM('Administrateur', 'Coach', 'Client') DEFAULT 'Client',
    nomComplet_coach VARCHAR(100),
    Supprimer VARCHAR(15)
);
