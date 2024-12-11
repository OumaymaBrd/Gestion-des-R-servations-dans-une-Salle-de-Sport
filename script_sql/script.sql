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


---Inserer Tableau activities : 
INSERT INTO activities (name, description, capacity, start_date, end_date, disponibility) 
VALUES 
    ('Yoga', 'Cours de Yoga pour améliorer la flexibilité et réduire le stress.', 20, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 4 MONTH), 1),
    ('Musculation', 'Séances de musculation avec équipement de pointe.', 15, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 4 MONTH), 1),
    ('Spinning', 'Cours intensif de spinning pour brûler des calories.', 25, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 4 MONTH), 1),
    ('Pilates', 'Amélioration de la posture et renforcement du corps avec le Pilates.', 18, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 4 MONTH), 1),
    ('Zumba', 'Danse énergique pour rester en forme tout en s\'amusant.', 30, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 4 MONTH), 1),
    ('Boxe', 'Entraînement physique et mental avec nos cours de boxe.', 12, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 4 MONTH), 1);
