/* CodeCollab Database Setup */
/* Jeff Bishop and Zeth Schlenker */
/* March 2014 */

DROP TABLE IF EXISTS Follow;
DROP TABLE IF EXISTS Promotion;
DROP TABLE IF EXISTS Comments;
DROP TABLE IF EXISTS Post;
DROP TABLE IF EXISTS Languages;
DROP TABLE IF EXISTS User;

CREATE TABLE User
(
	id					INT NOT NULL AUTO_INCREMENT,
	username			VARCHAR(20) NOT NULL,
	first_name			VARCHAR(35),
	last_name			VARCHAR(35),
	email				VARCHAR(255),
	pass_hash			VARCHAR(64) NOT NULL,
	salt				VARCHAR(64) NOT NULL,
	registration_date	DATETIME NOT NULL,
	profile_picture		TEXT,
	about				TEXT,
	name_visible		BOOLEAN,
	email_visible		BOOLEAN,
	about_visible		BOOLEAN,
	posts_visible		BOOLEAN,
	PRIMARY KEY (id)
);

/* "Language" is reserved */
CREATE TABLE Languages
(
	id					INT NOT NULL AUTO_INCREMENT,
	language_name		VARCHAR(35),
	PRIMARY KEY (id)
);

INSERT INTO Languages (language_name) VALUES
	('C'), ('C++'), ('Java'), ('Javascript'),
	('SQL'), ('PHP'), ('HTML'), ('Actionscript');

CREATE TABLE Post
(
	id					INT NOT NULL AUTO_INCREMENT,
	user_id				INT NOT NULL,
	title				VARCHAR(255),
	language_id			INT,
	tags				TEXT,
	content				TEXT NOT NULL,
	post_date			DATETIME NOT NULL,
	PRIMARY KEY (id),
	FOREIGN KEY (user_id) REFERENCES User (id),
	FOREIGN KEY (language_id) REFERENCES Languages (id)
);

/* "Comment" is reserved */
CREATE TABLE Comments
(
	id					INT NOT NULL AUTO_INCREMENT,
	post_id				INT NOT NULL,
	user_id				INT NOT NULL,
	content				TEXT NOT NULL,
	comment_date		DATETIME NOT NULL,
	PRIMARY KEY (id),
	FOREIGN KEY (post_id) REFERENCES Post (id),
	FOREIGN KEY (user_id) REFERENCES User (id)
);

CREATE TABLE Promotion
(
	id					INT NOT NULL AUTO_INCREMENT,
	post_id				INT NOT NULL,
	user_id				INT NOT NULL,
	promotion_date		DATETIME NOT NULL,
	PRIMARY KEY (id),
	FOREIGN KEY (post_id) REFERENCES Post (id),
	FOREIGN KEY (user_id) REFERENCES User (id)
);

CREATE TABLE Follow
(
	id					INT NOT NULL AUTO_INCREMENT,
	follower_id			INT NOT NULL,
	followee_id			INT NOT NULL,
	follow_date			DATETIME NOT NULL,
	PRIMARY KEY (id),
	FOREIGN KEY (follower_id) REFERENCES User (id),
	FOREIGN KEY (followee_id) REFERENCES User (id)
);