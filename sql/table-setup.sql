CREATE TABLE users (
	id INT AUTO_INCREMENT NOT NULL,
	first_name VARCHAR (25),
	last_name VARCHAR (25),
	username VARCHAR (60),
	email VARCHAR (60),
	password VARCHAR (255),
	signup_date DATE,
	profile_pic VARCHAR (255),
	num_posts INT,
	num_likes INT,
	user_closed VARCHAR (3),
	friend_array TEXT,
	PRIMARY KEY (id)
);


CREATE TABLE posts (
	id INT AUTO_INCREMENT NOT NULL,
	body TEXT,
	added_by VARCHAR (60),
	user_to VARCHAR (60),
	date_added DATETIME,
	user_closed VARCHAR (3),
	deleted VARCHAR (3),
	likes INT,
	image VARCHAR (500),
	PRIMARY KEY (id)
);


CREATE TABLE comments (
	id INT AUTO_INCREMENT NOT NULL,
	post_body TEXT,
	posted_by VARCHAR (60),
	posted_to VARCHAR (60),
	date_added DATETIME,
	removed VARCHAR (3),
	post_id INT,
	PRIMARY KEY (id)
);


CREATE TABLE likes (
	id INT AUTO_INCREMENT NOT NULL,
	username VARCHAR (60),
	post_id INT,
	PRIMARY KEY (id)
);


CREATE TABLE friend_requests (
	id INT AUTO_INCREMENT NOT NULL,
	user_to VARCHAR (60),
	user_from VARCHAR (60),
	PRIMARY KEY (id)
);


CREATE TABLE messages (
	id INT AUTO_INCREMENT NOT NULL,
	user_to VARCHAR (60),
	user_from VARCHAR (60),
	body TEXT,
	date DATETIME,
	opened VARCHAR (3),
	viewed VARCHAR (3),
	deleted VARCHAR (3),
	PRIMARY KEY (id)
);


CREATE TABLE notifications (
	id INT AUTO_INCREMENT NOT NULL,
	user_to VARCHAR (60),
	user_from VARCHAR (60),
	message TEXT,
	link VARCHAR (100),
	datetime DATETIME,
	opened VARCHAR (3),
	viewed VARCHAR (3),
	PRIMARY KEY (id)
);


CREATE TABLE trends (
	id INT AUTO_INCREMENT NOT NULL,
	title VARCHAR (60),
	hits INT,
	PRIMARY KEY (id)
);
