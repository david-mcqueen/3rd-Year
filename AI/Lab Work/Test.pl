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

delete1(X, [X | Tail], Tail).

delete1(X, [Y | Tail], [Y | NewTail]):-
	delete1(X, Tail, NewTail).


evenLength([], Length):-
	RemainderLength is mod(Length, 2),
	RemainderLength = 0.

evenLength([Head | Tail], Length):-
	NewLength is Length + 1,
	evenLength(Tail, NewLength).

oddLength([], Length):-
	RemainderLength is mod(Length, 2),
	RemainderLength = 1.

oddLength([Head | Tail], Length):-
	NewLength is Length + 1,
	oddLength(Tail, NewLength).

reverse1([], []).

reverse1([Head | Tail], ReversedList):-
	reverse1(Tail, ReversedTail),
	append(ReversedTail, [Head], ReversedList).

	% Was getting a bit confused and stuck, so had a google.
	% http://www.learnprolognow.org/lpnpage.php?pagetype=html&pageid=lpn-htmlse25


palindrome(List):-
	reverse1(List, List).


insertInOrder(X, [Head | Tail], Result):-

	append([Result], [Head], NewTail),
	insertInOrder(X, Tail, NewTail);
	print(NewTail),


	append(Result, [X], StageResult),
	append(StageResult, Tail, FinalResult),
	print(FinalResult).


insert(X, [], [X]).

insert(X, [Y|Rest], [X,Y|Rest]) :-
    X @< Y, !.

insert(X, [Y|Rest0], [Y|Rest]) :-
    insert(X, Rest0, Rest).
 %http://stackoverflow.com/questions/9004265/inserting-x-in-its-correct-position-in-a-sorted-list



insertionSort([], Result):-
	print(Result).

insertionSort([Head | Tail], Sorted):-
	insert(Head, Sorted, Result),
	insertionSort(Tail, Result).



means(0, zero).
means(1, one).
means(2, two).
means(3, three).
means(4, four).
means(5, five).
means(6, six).
means(7, seven).
means(8, eight).
means(9, nine).


translate([],[]).

translate([], List):-
	print(List).

translate([Head | Tail], List2):-
	means(Head, Name),
	append(List2, [Name], Result),
	translate(Tail, Result).
	

