CREATE TABLE users (
	id INT AUTO_INCREMENT NOT NULL,
	first_name VARCHAR (25),
	last_name VARCHAR (25),
	username VARCHAR (50),
	email VARCHAR (50),
	password VARCHAR (255),
	signup_date DATE,
	profile_pic VARCHAR (255),
	num_posts INT,
	num_likes INT,
	user_closed VARCHAR (3),
	friends_array TEXT,
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
	PRIMARY KEY (id)
);


CREATE TABLE post_comments (
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

