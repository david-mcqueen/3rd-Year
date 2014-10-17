%simple backward chaining KBS for flat leak problem. 
%if then rules and list of facts which the user 
%be asked for - askable
% Do not need to understand the operators below, always used in rule based system shells 
:-op(800,fx,if).        %set operator precedence 'and, or ' etc for if 
:-op(700,xfx,then).  	%then rules 
:-op(300,xfy,or). 
:-op(200,xfy,and).

%Each rule has an argument variable Trace, 
% this is just a list of the conditions and consequents from each if .. then.. rule

is_true(P,Trace):-            %look for a rule with P as the consequent (R.H..side) 
  if Condition then P, 
  is_true( Condition,[[P,Condition]|Trace]).  %found rule so now try to prove condition (L.H..side)

is_true(P1 and P2,Trace):-    % if trying to prove something containing AND's then split into 2 parts 
  is_true(P1,Trace),        % try to prove each part individually 
  is_true(P2,Trace).

is_true(P1 or P2,Trace):- 
  is_true(P1,Trace);  %as above but with an OR 
  is_true(P2,Trace).

is_true(P,Trace):-  %couldn't find any rules about it 
  askable(P),        %can we ask the user about it - check askable list 
  question(P,Response,Trace),    % ask the user 
  yes(Response).            % only if user says yes - continue this line of reasoning

question(P,Response,Trace):- 
  write('Is the '), write(P), nl, 
  read(Response), 
  yes_no(Response),!.       %validate user response - if not a 'n' or a 'y' then assume 
                                        %they have asked 'why' - see below 
question(P,Response,Trace):- 
  whyTrace(Trace),            %provide a why trace - list of conditions and consequents 
  question(P,Response,Trace),!.    %re-ask the question

whyTrace([]). 
whyTrace([[X,Y]|Tail]):- 
  write('There is a '), 
  write(X),write(' if '), 
  write(Y),nl, 
  whyTrace(Tail).

askable(P):- 
  mymember(P,[kitchen_dry,bathroom_dry,window_closed,no_rain, funny_smell, flat_mate_out]).

yes_no(n). 
yes_no(y). 
yes(y).

mymember(X,[X|_]). 
mymember(X,[_|Tail]):- 
  mymember(X,Tail).

%Domain Rules and Facts

if hall_wet and kitchen_dry then 
leak_in_bathroom.

if hall_wet and bathroom_dry then 
problem_in_kitchen.

if window_closed or no_rain then 
no_water_from_outside.

if problem_in_kitchen and no_water_from_outside then 
leak_in_kitchen.

if funny_smell and flat_mate_out then
hall_wet.

if funny_smell then
rug_wet.