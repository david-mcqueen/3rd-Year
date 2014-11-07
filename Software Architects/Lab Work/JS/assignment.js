//David McQueen - 10153465
function Vehicle(){
	this.numWheels = 4;
	this.colour = "yellow";
}

Vehicle.prototype.getWheels = function(){
	 return this.wheels;
}

Vehicle.prototype.getColour = function(){
	 return this.colour;
}

Vehicle.prototype.blowHorn = function(){
	print("Beep!");
}

function Taxi(badgeNumber){
	this.badgeNumber = badgeNumber;
}
Taxi.prototype = new Vehicle();


var fleet = [],
	i;


for (i = 0; i < 5; i++){
	fleet[i] = new Taxi(i);
}

for (i = 0; i < fleet.length; i++){
	print("Taxi with badge number " + fleet[i].badgeNumber + " is " + fleet[i].colour);
}

Taxi.prototype.colour = "White";

for (i = 0; i < fleet.length; i++){
	print("Taxi with badge number " + fleet[i].badgeNumber + " is " + fleet[i].colour);
	fleet[i].blowHorn();
}
