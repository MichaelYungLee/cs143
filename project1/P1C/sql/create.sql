create table Movie(
	id int,
	title varchar(100) not null,
	year int not null,
	rating varchar(10),
	company varchar(50) not null,
	primary key (id) -- Every Movie has a unique ID
) engine=InnoDB;

create table Actor(
	id int,
	last varchar(20) not null,
	first varchar(20) not null,
	sex varchar(6) not null,
	dob date not null,
	dod date,
	primary key (id), -- Every Actor has a unique ID. shared with Director
	check(dob <= dod) -- dod must be equal or less than the dob
) engine=InnoDB;

create table Director(
	id int,
	last varchar(20) not null,
	first varchar(20) not null,
	dob date not null,
	dod date,
	primary key (id), -- Every Director has a unique ID, shared with Actor
	check(dob <= dod) -- dod must be equal or less than the dob
) engine=InnoDB;

create table MovieGenre(
	mid int,
	genre varchar(20),
	primary key (mid, genre),
	foreign key (mid) references Movie (id) -- mid needs to match with primary key Movie.id
) engine=InnoDB;

create table MovieDirector(
	mid int,
	did int,
	primary key (mid, did),
	foreign key (mid) references Movie (id), -- mid needs to match with primary key Movie.id
	foreign key (did) references Director (id) -- did needs to match with primary key Director.id
) engine=InnoDB;

create table MovieActor(
	mid int,
	aid int,
	role varchar(50),
	primary key (mid, aid),
	foreign key (mid) references Movie (id), -- mid needs to match with primary key Movie.id
	foreign key (aid) references Actor (id) -- aid needs to match with primary key Actor.id
) engine=InnoDB;

create table Review(
	name varchar(20),
	time timestamp not null,
	mid int,
	rating int not null,
	comment varchar(500),
	primary key (name, mid),
	foreign key (mid) references Movie (id), -- mid needs to match with primary key Movie.id
	check (rating >= 0 and rating <= 5) -- Rating must fall between 0 and 5
);

create table MaxPersonID(
	id int not null
) engine=InnoDB;

create table MaxMovieID(
	id int not null
) engine=InnoDB; 
