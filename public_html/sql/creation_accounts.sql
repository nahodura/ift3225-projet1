-- table de users (extrait de code pris du cours)
CREATE TABLE IF NOT EXISTS accounts (
  account_id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  account_name VARCHAR(255) NOT NULL UNIQUE,
  account_email VARCHAR(255) NOT NULL UNIQUE,
  account_passwd VARCHAR(255) NOT NULL,
  account_reg_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  account_enabled TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
  account_admin TINYINT(1) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- table pour les sessions actives (extrait de code pris du cours)
CREATE TABLE IF NOT EXISTS account_sessions (
  session_id VARCHAR(255) NOT NULL PRIMARY KEY,
  account_id INT(10) UNSIGNED NOT NULL,
  login_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

