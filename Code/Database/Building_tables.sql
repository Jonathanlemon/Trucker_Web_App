USE 4910_team4_database;

SELECT * FROM users 
WHERE user_id;

CREATE TABLE IF NOT EXISTS Organizations (
	org_id			int not null auto_increment,
    org_email		varchar(255),
    org_name		varchar(255) not null,
    org_desc		varchar(255),
    phone			varchar(255),
    primary key (org_id)
);

CREATE TABLE IF NOT EXISTS Admins (
	admin_id		int not null,
    email			varchar(255),
    passwrd			varchar(255) not null,
    first_name 		varchar(255) not null,
    last_name		varchar(255) not null,
    foreign key (admin_id) references users(user_id),
    primary key (admin_id)
);

CREATE TABLE IF NOT EXISTS Sponsors (
	sponsor_id		int not null,
    org_id			int not null,
    email			varchar(255),
    passwrd			varchar(255) not null,
    first_name 		varchar(255) not null,
    last_name		varchar(255) not null,
    foreign key (sponsor_id) references users(user_id),
    foreign key (org_id) references Organizations(org_id),
    primary key (sponsor_id)
);

CREATE TABLE IF NOT EXISTS Drivers (
	driver_id		int not null,
    org_id			int not null,
    email			varchar(255),
    passwrd			varchar(255) not null,
    first_name 		varchar(255) not null,
    last_name		varchar(255) not null,
    address			varchar(255) not null,
    foreign key (driver_id) references users(user_id),
    foreign key (org_id) references Organizations(org_id),
    primary key (driver_id)
);

CREATE TABLE IF NOT EXISTS Points (
	point_id		int not null auto_increment,
    driver_id		int not null,
    sponsor_id		int not null,
    amt_change		decimal(4,2) not null,
    first_name 		varchar(255) not null,
    last_name		varchar(255) not null,
    time_stamp		
    primary key (admin_id)
);

