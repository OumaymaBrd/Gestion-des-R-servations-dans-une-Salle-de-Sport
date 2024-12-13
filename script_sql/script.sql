-- Nom de la base de données
CREATE DATABASE IF NOT EXISTS Salle_Sport;
USE Salle_Sport;

-- Création de la table Members
CREATE TABLE Members (
    member_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL,
    phone_number VARCHAR(15) NOT NULL
);

-- Création de la table Activities
CREATE TABLE Activities (
    activity_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    description TEXT,
    capacity INT(11) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    disponibility TINYINT(1) DEFAULT 1
);

-- Création de la table Reservations
CREATE TABLE Reservations (
    reservation_id INT PRIMARY KEY AUTO_INCREMENT,
    member_id INT NOT NULL,
    activity_id INT NOT NULL,
    reservation_date DATETIME NOT NULL,
    status ENUM('Confirmed', 'Cancelled') DEFAULT 'Confirmed',
    FOREIGN KEY (member_id) REFERENCES Members(member_id),
    FOREIGN KEY (activity_id) REFERENCES Activities(activity_id)
);

-- Création de la table Tableau_activite
CREATE TABLE Tableau_activite (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom_complet_membre VARCHAR(100) NOT NULL,
    Admit_activite ENUM('oui', 'Non') DEFAULT 'Non',
    nomComplet_coach VARCHAR(100),
    Supprimer ENUM('0','1') DEFAULT '0',
    activity_id INT,
    FOREIGN KEY (activity_id) REFERENCES Activities(activity_id)
);

-- Création de la table Tableau_Authentifier
CREATE TABLE Tableau_Authentifier (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom_complet_membre VARCHAR(50) NOT NULL,
    Post ENUM('Administrateur', 'Coach', 'Client') DEFAULT 'Client',
    nomComplet_coach VARCHAR(100),
    Supprimer VARCHAR(15)
);

-- Insertion de données initiales dans Activities
INSERT INTO activities (name, description, capacity, start_date, end_date, disponibility) 
VALUES 
    ('Yoga', 'Cours de Yoga pour améliorer la flexibilité et réduire le stress.', 20, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 4 MONTH), 1),
    ('Musculation', 'Séances de musculation avec équipement de pointe.', 15, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 4 MONTH), 1),
    ('Spinning', 'Cours intensif de spinning pour brûler des calories.', 25, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 4 MONTH), 1),
    ('Pilates', 'Amélioration de la posture et renforcement du corps avec le Pilates.', 18, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 4 MONTH), 1),
    ('Zumba', 'Danse énergique pour rester en forme tout en s\'amusant.', 30, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 4 MONTH), 1),
    ('Boxe', 'Entraînement physique et mental avec nos cours de boxe.', 12, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 4 MONTH), 1);

-- Insertion simple dans Activities
INSERT INTO Activities (name, description, capacity, start_date, end_date, disponibility)
VALUES ('Cardio', 'Séance de cardio pour améliorer l'endurance', 15, '2023-06-01', '2023-09-30', 1);

-- Suppression simple d'une ligne de Activities
DELETE FROM Activities WHERE activity_id = 1;

-- Mise à jour simple d'une ligne dans Activities
UPDATE Activities 
SET capacity = 20, 
    end_date = '2023-10-31'
WHERE activity_id = 2;

-- Jointure simple entre Activities et Reservations
SELECT Activities.name, Reservations.reservation_date, Reservations.status
FROM Activities
INNER JOIN Reservations ON Activities.activity_id = Reservations.activity_id
LIMIT 5;

-- Affichage de toutes les activités
SELECT * FROM activities;

INSERT INTO Activities (name, description, capacity, start_date, end_date, disponibility)
VALUES ('Cardio', 'Séance de cardio pour améliorer l'endurance', 15, '2023-06-01', '2023-09-30', 1);

DELETE FROM Activities WHERE activity_id = 1;
UPDATE Activities 
SET capacity = 20, 
    end_date = '2023-10-31'
WHERE activity_id = 2;

UPDATE Activities 
SET capacity = 20, 
    end_date = '2023-10-31'
WHERE activity_id = 2;

SELECT Activities.name, Reservations.reservation_date, Reservations.status
FROM Activities
INNER JOIN Reservations ON Activities.activity_id = Reservations.activity_id
LIMIT 5;

SELECT Activities.name AS activity_name, 
       Reservations.reservation_date, 
       Reservations.status
FROM Activities
INNER JOIN Reservations ON Activities.activity_id = Reservations.activity_id
LIMIT 5;

DELETE FROM Activities WHERE activity_id = 3;