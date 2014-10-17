room(bedroom).
room(hall).
room(living).
room(dining).
room(kitchen).
door(bedroom,hall).
door(hall,living).
door(hall,dining).
door(hall,kitchen).
door(dining,kitchen).
object(knife).
in_room(knife,bedroom).
contains(X,Object):-
	room(X),
	object(Object),
	in_room(Object,X).


%is_route(X,Y):-
	%door(X,Y).

%is_route(X,Y):-
	%door(X,NextRoom),
	%write(X),
	%is_route(NextRoom,Y).
	
is_route(From, To, [To]) :-
	door(From, To).

is_route(From, To, [Next, List]) :-
	door(From, Next),
	is_route(Next, To, List).
	
	
