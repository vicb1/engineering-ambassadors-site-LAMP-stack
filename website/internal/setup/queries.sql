CREATE TABLE IF NOT EXISTS users
(
id INT NOT NULL AUTO_INCREMENT,
rcs_id VARCHAR(15) NOT NULL,
rin VARCHAR(9),
title VARCHAR(50) NOT NULL DEFAULT "Member",
year ENUM('Freshman','Sophomore','Junior','Senior','n/a') NOT NULL DEFAULT 'n/a',
major VARCHAR(50) NOT NULL DEFAULT "n/a",
email VARCHAR(50),
hometown VARCHAR(200),
first_name VARCHAR(50) NOT NULL,
middle_name VARCHAR(50),
last_name VARCHAR(50) NOT NULL,
entry_date DATE,
grad_date DATE,
favorite_quote TEXT,
is_admin BOOLEAN NOT NULL DEFAULT 0,
is_disabled BOOLEAN NOT NULL DEFAULT 0,
img_takedown_msg TEXT,  #Since mods can take down profile pictures users need a way to be notified of this
img_path TEXT,
PRIMARY KEY(id),
UNIQUE(rcs_id)
);

#Map users to extracurricular activities
CREATE TABLE IF NOT EXISTS user_to_ecas
(
id INT NOT NULL AUTO_INCREMENT,
user_id INT NOT NULL,
eca_name VARCHAR(500) NOT NULL,
PRIMARY KEY(id),
FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE
);

#Map users to job experience (internships/co-ops)
CREATE TABLE IF NOT EXISTS user_to_jobs
(
id INT NOT NULL AUTO_INCREMENT,
user_id INT NOT NULL,
job_name VARCHAR(500) NOT NULL,
PRIMARY KEY(id),
FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS user_to_pres
(
id INT NOT NULL AUTO_INCREMENT,
user_id INT NOT NULL,
pres_id INT NOT NULL,
FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE,  #If user_id's id is deleted, so will this entry
FOREIGN KEY(pres_id) REFERENCES presentations(id) ON DELETE CASCADE,  #If pres_id's id is deleted, so will this entry
UNIQUE (user_id,pres_id), #We want only one user_id -> pres_id pair, no repeats
PRIMARY KEY(id)
);

CREATE TABLE IF NOT EXISTS presentations
(
id INT NOT NULL AUTO_INCREMENT,
name VARCHAR(255) NOT NULL,
description TEXT,
created_at DATETIME NOT NULL,
updated_at DATETIME,
img_takedown_msg TEXT,  #Since mods can take down profile pictures users need a way to be notified of this
img_path TEXT,
PRIMARY KEY(id)
);

CREATE TABLE IF NOT EXISTS school_signups
(
id INT NOT NULL AUTO_INCREMENT,
school_district VARCHAR(255),
school_contact VARCHAR(255),
department VARCHAR(255),
phone_num VARCHAR(50),
email VARCHAR(50),
address VARCHAR(255),
how_learn TEXT,
description TEXT,
created_at DATETIME NOT NULL,
PRIMARY KEY(id)
);

CREATE TABLE IF NOT EXISTS testimonials
(
id INT NOT NULL AUTO_INCREMENT,
testimonial TEXT NOT NULL,
name VARCHAR(255),
signature TEXT,
created_at DATETIME NOT NULL,
PRIMARY KEY(id)
);

#Useful scripts (don't enter these in unless you need them for testing!)

#Empty users table without complaining about foreign key constraints
SET FOREIGN_KEY_CHECKS=0; TRUNCATE users; SET FOREIGN_KEY_CHECKS=1;

#Empty presentations table without complaining about foreign key constraints
SET FOREIGN_KEY_CHECKS=0; TRUNCATE presentations; SET FOREIGN_KEY_CHECKS=1;

#Drop users table without complaining about foreign key constraints
SET FOREIGN_KEY_CHECKS=0; DROP TABLE users; SET FOREIGN_KEY_CHECKS=1;

#Drop presentations table without complaining about foreign key constraints
SET FOREIGN_KEY_CHECKS=0; DROP TABLE presentations; SET FOREIGN_KEY_CHECKS=1;

#Empty everything
SET FOREIGN_KEY_CHECKS=0; TRUNCATE users; SET FOREIGN_KEY_CHECKS=1;
SET FOREIGN_KEY_CHECKS=0; TRUNCATE presentations; SET FOREIGN_KEY_CHECKS=1;