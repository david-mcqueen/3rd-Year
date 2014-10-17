%forward chaining Production system
:-op(800,fx,if).        %set operators for if 
:-op(700,xfx,then).     %then rules 
:-op(300,xfy,or). 
:-op(200,xfy,and). 
 

% dynamic(....) allows predicate inside brackets fact to be asserted and retracted, 
% here were are making fact (/1 means fact has 1 argument) dynamic so we can add and 
% take facts from working memory.

:-dynamic(fact/1).

fact(has(smith,raisedIntraocularPressure)).        %list of facts 
fact(has(smith,previousHeartAttack)).
fact(has(smith,leftQuadraticPain)).
fact(has(smith,heavySmoker)).
fact(has(jones,asthmatic)).
fact(has(jones,raisedIntraocularPressure)).
fact(has(jones,heavySmoker)).



 %for ex4 ... bagof(X,fact(X),List).

printAllFacts:-
  bagof(X,fact(X),List),
  print(List).

forward:- 
  new_fact(P), 
  !, 
  write('New fact '), write(P),nl, 
  asserta(fact(P)),                        %adds a fact to working memory 
  forward 
; 
  write('no more facts').

new_fact(Action):- 
  if Condition then Action, 
  not(fact(Action)), 
  composedFact(Condition).

composedFact(Cond):- 
  fact(Cond).

composedFact(C1 and C2):- 
  composedFact(C1), 
  composedFact(C2).

composedFact(C1 or C2):- 
  composedFact(C1) 
; 
  composedFact(C2).

if has(Person,riskOfHeartAttack) and has(Person,previousHeartAttack)        %list of rules
then need(Person,digitalis). 

if has(Person,leftQuadraticPain) and has(Person,highBloodPressure)
then has(Person,riskOfHeartAttack).

if has(Person,raisedIntraocularPressure)
then has(Person, highBloodPressure).

if has(Person,highBloodPressure) and has(Person,heavySmoker)
then has(Person, riskOfHeartAttack).

if has(Person,asthmatic) and has(Person,riskOfHeartAttack) and has(Person, previousHeartAttack)
then need(Person, preminol-V).

not(X):- 
 X,!,fail;true.


