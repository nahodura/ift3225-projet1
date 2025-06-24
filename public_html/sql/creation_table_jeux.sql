
CREATE TABLE IF NOT EXISTS jeux (
  jeu_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(255) NOT NULL,
  description TEXT,
  image VARCHAR(255),
  genre VARCHAR(100),
  plateforme VARCHAR(100),
  id_utilisateur INT UNSIGNED,
  date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_utilisateur) REFERENCES accounts(account_id)
);
