users (
	uid INT PRIMARY KEY AUTO_INCREMENT,
	name VARCHAR(15) UNIQUE,
	password CHAR(32),
	role ENUM( 'guest', 'user', 'moderator', 'gamemaker', 'admin' ) DEFAULT 'guest',
	date TIMESTAMP );
comments (
	id INT PRIMARY KEY AUTO_INCREMENT,
	user INT DEFAULT 1,
	card INT NOT NULL,
	text VARCHAR(255),
	date TIMESTAMP );
