select concat(first, " ", last) as "Actors in 'Die Another Day'"
from Movie, MovieActor, Actor
where title = 'Die Another Day' and Movie.id = MovieActor.mid and Actor.id = aid;
-- Give the first and last names of all actors that were in Die Another Day

select count(aid) from (
	select aid
	from MovieActor
	group by aid
	having count(aid) > 1
) as actors_in_multiple_movies;
-- Returns the count of all actors that are in multiple movies

select title
from Movie
where year between 1980 and 1989 and rating = "PG";
-- Returns the titles of movies made between 1980 and 1989 inclusive with a rating of PG
