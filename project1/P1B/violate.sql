-- Primary key violations
insert into Movie values(2495,"Interstellar","2014","PG-13","Legendary Pictures");
-- Attempts to insert a movie into the table with duplicate id, violates primary key 
-- ERROR 1062 (23000): Duplicate entry '2495' for key 'PRIMARY'

insert into Actor values(23425,"Goldblum","Jeff","Male","1952-10-22",NULL);
-- Attempts to insert a duplicate actor, violates primary key 
-- ERROR 1062 (23000): Duplicate entry '2495' for key 'PRIMARY'

insert into Director values(NULL,"Spielberg","Steven","Male","1946-12-18",NULL);
-- Attempts to insert a null primary key into Director
-- ERROR 1048 (23000): Column 'id' cannot be null 

-- Foreign key violations
update MovieGenre set mid = NULL where mid = 2495;
-- Violates foreign key reference in MovieGenre
-- ERROR 1048 (23000): Column 'mid' cannot be null

update MovieDirector set mid = 4760 where did = 58777;
-- Violates foreign key reference in MovieDirector
-- ERROR 1452 (23000): Cannot add or update a child row: a foreign key constraint fails (`CS143`.`MovieDirector`, CONSTRAINT `MovieDirector_ibfk_1` FOREIGN KEY (`mid`) REFERENCES `Movie` (`id`))

delete from Director where id = 58777;
-- Violates foreign key reference in MovieDirector
-- ERROR 1451 (23000): Cannot delete or update a parent row: a foreign key constraint fails (`CS143`.`MovieDirector`, CONSTRAINT `MovieDirector_ibfk_2` FOREIGN KEY (`did`) REFERENCES `Director` (`id`))

delete from Movie where id = 2895;
-- Violates foreign key reference in MovieActor of Movie
-- ERROR 1451 (23000): Cannot delete or update a parent row: a foreign key constraint fails (`CS143`.`MovieActor`, CONSTRAINT `MovieActor_bifk_1` FOREIGN KEY (`mid`) REFERENCES `Movie` (`id`))

delete from Actor where id = 23426;
-- Violates foreign key reference in MovieActor
-- ERROR 1451 (23000): Cannot delete or update a parent row: a foreign key constraint fails (`CS143`.`MovieActor`, CONSTRAINT `MovieActor_ibfk_2` FOREIGN KEY (`aid`) REFERENCES `Actor` (`id`))

insert into Review values("John Doe","2018-1-1 08:08:08",5000,4,NULL);
-- Violates foreign key reference in Review of Movie
-- ERROR 1452 (23000): Cannot add or update a child row: a foreign key constraint fails (`CS143`.`Review`, CONSTRAINT `Review_ibfk_1` FOREIGN KEY (`mid`) REFERENCES `Movie` (`id`))

-- Check constraint violations
update Actor set dod = "1952-10-21" where id = 23426;
-- Violates check constraint that dob <= dod

update Director set dod = "1900-10-20" where id = 58777;
-- Violates check constraint that dob <= dod 

insert into Review values("John Doe","2018-1-1 08:08:08",2496,10,NULL);
-- Violates check constraint that the rating must be between 0 and 5
