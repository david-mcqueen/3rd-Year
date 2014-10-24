%forward chaining Production system
:-op(800,fx,if).        %set operators for if 
:-op(700,xfx,then).     %then rules 
:-op(300,xfy,or). 
:-op(200,xfy,and). 
 

% dynamic(....) allows predicate inside brackets fact to be asserted and retracted, 
% here were are making fact (/1 means fact has 1 argument) dynamic so we can add and 
% take facts from working memory.

:-dynamic(fact/1).


fact(has(myBike,rr_Present)).   
fact(has(myBike,voltageGreaterThan14_8)). 

printAllFacts:-
  bagof(X,fact(X),List),
  print(List).

forward:- 
  new_fact(P), 
  !, 
  write('New fact '), write(P),nl, 
  asserta(fact(P)), %adds a fact to working memory 
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

if has(Bike, rr_Present)
then has(Bike, rr_Present).

if has(Bike, rr_Present) and has(Bike, voltageGreaterThan14_8)
then has(Bike, rr_fault).

if has(Bike, rr_Present) and has(Bike, voltageLessThan14_8) and has(bike, meterBetween3and10)
then has(Bike, fieldWindingFault).

if has(Bike, resistanceLessThan3_6orGreaterThan6)
then has(Bike, resistanceLessThan3_6orGreaterThan6).

if has(Bike, resistanceBetween4and6)
then has(Bike, resistanceBetween4and6).

if has(Bike, fieldWindingFault) and has(resistanceLessThan3_6orGreaterThan6)
then has(Bike, rotorFault).

if has(Bike, fieldWindingFault) and has(Bike, resistanceBetween4and6)
then has(Bike, wiringBrushFault).



not(X):- 
 X,!,fail;true.


