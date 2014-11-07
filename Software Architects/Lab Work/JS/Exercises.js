


function FizzBuzz(){
	var i,
		output;

	for (i = 1; i < 100; i++){
		output = i;

		
		if(i % 3 == 0 && i % 5 == 0){
			output = "FizzBuzz"
		}else if(i % 3 == 0){
			output = "Fizz"
		}else if(i % 5 == 0){
			output = "Buzz"
		}else{
			output = i;
		}

	debug(output);
	}
}

function shout(input){
	debug(input.toUpperCase() + "!");
}

String.prototype.shout = function() {
	return (this.toUpperCase() + "!");
};

function Vehicle(wheels, colour){
	this.wheels = wheels;
	this.colour = colour;
}

Vehicle.prototype.getWheels = function(){
	 return this.wheels;
}

Vehicle.prototype.getColour = function(){
	 return this.colour;
}

function Bike(colour){
	this.wheels = 2;
	this.colour = colour;
}

Bike.prototype.getWheels = function(){
	return this.wheels;
}

Bike.prototype.getColour = function(){
	return this.colour;
}