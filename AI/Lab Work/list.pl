split([H|Tail], H, Tail).


writeList([]).
writeList([Head|Tail]):-
	write(Head),
	nl,
	writeList(Tail).

isEmpty([]).

mymember(X, [X | Tail]).

mymember(X, [Y | Tail]):-
	mymember(X,Tail).

mylength([], Length):-
	write(Length).

mylength([Head| Tail], LengthTail):-
	NewLength is LengthTail+1,
	mylength(Tail, NewLength).

