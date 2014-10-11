writeList([]).

writeList([Head | List]):-
	write(Head),
	nl,
	writeList(List).

%mymember(X, [X | Tail]).

%mymember(X, [Head | Tail]):-
	%mymember(X, Tail).

mymember(X, L):-
	append(_, [X | _], L).
	% we are not interested in the two sub lists
	% only that they can be achieved

mylength([], NewLength):-
	write(NewLength).

mylength([Head | Tail], ListLength):-
	NewLength is ListLength + 1,
	mylength(Tail, NewLength).

delete_last_3(L, L1):-  
	append(L1, [_,_,_], L).
	% http://stackoverflow.com/questions/7682473/delete-last-3-elements-of-list-l-to-make-list-l1-in-prolog

delete_first_3(L, L1):-
	append([_,_,_], L1, L).

deleteFirstAndLast3(L, L2):-
	append([_,_,_], LT, L),
	delete_last_3(LT, L2).

delete(X, [X | Tail], Tail).

delete(X, [Y | Tail], [Y | NewTail]):-
	delete(X, Tail, NewTail).
