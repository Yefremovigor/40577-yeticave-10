CREATE DATABASE yeticave
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;

USE yeticave;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  create_date DATETIME DEFAULT CURRENT_TIMESTAMP,
  email CHAR(128) NOT NULL UNIQUE,
  name CHAR(64) NOT NULL,
  password CHAR(64) NOT NULL,
  avatar TEXT,
  contacts TEXT NOT NULL
);

CREATE TABLE bets (
  id INT AUTO_INCREMENT PRIMARY KEY,
  create_date DATETIME DEFAULT CURRENT_TIMESTAMP,
  bet INT NOT NULL,
  user_id INT NOT NULL,
  lot_id INT NOT NULL
);

CREATE TABLE lots (
  id INT AUTO_INCREMENT PRIMARY KEY,
  create_date DATETIME DEFAULT CURRENT_TIMESTAMP,
  title CHAR(64) NOT NULL,
  description TEXT NOT NULL,
  img TEXT NOT NULL,
  start_price INT NOT NULL,
  finish_date DATETIME,
  bet_step INT NOT NULL,
  author_id INT NOT NULL,
  winner_id INT,
  category_id INT NOT NULL
);

CREATE TABLE categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name CHAR(64) NOT NULL,
  alias CHAR(64) NOT NULL
);

CREATE INDEX finish_date ON lots(finish_date);
CREATE INDEX author_id ON lots(author_id);
CREATE INDEX category_id ON lots(category_id);
CREATE INDEX user_id ON bets(user_id);
CREATE INDEX lot_id ON bets(lot_id);

CREATE FULLTEXT INDEX lots_ft_search ON lots(title, description);
