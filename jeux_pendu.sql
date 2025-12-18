CREATE DATABASE jeux_pendu;
USE jeux_pendu;

-- =====================
-- TABLE USERS
-- =====================
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    pseudo VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    last_login DATETIME
);

-- =====================
-- TABLE MOTS
-- =====================
CREATE TABLE mots (
    id INT PRIMARY KEY AUTO_INCREMENT,
    mots VARCHAR(100) NOT NULL,
    taille INT NOT NULL,
    difficulte ENUM ('facile', 'moyen', 'difficile') NOT NULL
);

-- =====================
-- TABLE JEUX
-- =====================
CREATE TABLE jeux (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    mots_id INT NOT NULL,
    tentatives INT DEFAULT 0,
    victory TINYINT(1) DEFAULT 0,
    points INT DEFAULT 0,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (mots_id) REFERENCES mots(id)
);

-- =====================
-- INSERT USERS 
-- =====================
INSERT INTO users (pseudo, email, password)
VALUES
('Marc', 'Marc@mail.com', 'salut'),
('Marie', 'Marie@mail.com', 'salut');

-- =====================
-- INSERT MOTS
-- =====================
INSERT INTO mots (mots, taille, difficulte)
VALUES
-- facile
('souris', 6, 'facile'),
('tomate', 6, 'facile'),
('riz', 3, 'facile'),

-- moyen
('montagne', 8, 'moyen'),
('saucisse', 8, 'moyen'),
('etudiant', 8, 'moyen'),

-- difficile
('ordinateur', 10, 'difficile'),
('acoustique', 10, 'difficile'),
('constitution', 12, 'difficile');
