parent(fred,sally).
parent(fred,peter).
parent(janet,peter).
parent(janet,sally).
parent(pervis,adil).
parent(sandra,adil).
parent(sally,barry).
parent(adil,barry).
parent(barry,gena).
parent(barry,bill).
parent(agatha,bill).
parent(agatha,gena).

female(janet).
female(sally).
female(sandra).
female(agatha).
female(gena).

male(fred).
male(pervis).
male(peter).
male(adil).
male(barry).

	
is_grandparent(Grandparent,Grandchild):-
	parent(Grandparent,Parent),
	parent(Parent,Grandchild).

is_mother(M,Offspring):-
	female(M),
	parent(M,Offspring).
	
is_father(F,Offspring):-
	male(F),
	parent(F,Offspring).
	
is_married(X,Y):-
	male(X),
	female(Y),
	parent(X,Child),
	parent(Y,Child).

is_ancestor(X,Y):-
	parent(X,Y).

is_ancestor(X,Y):-
	parent(X, Child),
	is_ancestor(Child,Y).
	
is_ancestorReverse(X,Y):-
	parent(X,Y).

is_ancestorReverse(X,Y):-
	parent(Older,Y),
	is_ancestorReverse(X, Older).
