import static java.lang.System.*;

import java.io.Console;
import java.io.IOException;
import java.util.ArrayList;

public class HelloWorld {

	public static void main(String[] args) {
		// TODO Auto-generated method stub
		
		helloWorld();
		try {
			System.in.read();
		} catch (IOException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
		FizzBuzz();
		try {
			System.in.read();
		} catch (IOException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
		gridStar();
		try {
			System.in.read();
		} catch (IOException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
		triangleStar();
		try {
			System.in.read();
		} catch (IOException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
		AnimalInterface();
		try {
			System.in.read();
		} catch (IOException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
		AnimalClass();
		try {
			System.in.read();
		} catch (IOException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
		/*
		 * 4c)
		 * A class can be instantiated, whereas an Interface cannot
		 * 		We could create an object AnimalClass, which would be valid
		 * 		We couldn't create an object Animal (Interface)
		 * 
		 * A class can only inherit one other class, whereas it is possible to implement many interfaces.
		 * 
		 * An Interface is more like a contract of how things should be, and is more flexible on how it should be implemented in a class.
		 * The CPU cost of an Interface is less than that of a class
		 * 
		 * A class is more rigid, and tells the other class (that extends it) how things should be.
		 * 
		 * http://stackoverflow.com/questions/1913098/what-is-the-difference-between-an-interface-and-abstract-class
		 * 
		 * 5) 
		 * Inside methodA: value = 15
		 * 		10 Is passed it. 5 Is added, so 15
		 * After MethodA: x = 10
		 * 		The value passed into methodA is not returned, so here we are still working with the original (10)
		 * 
		 * Inside methodB: n.value = 16
		 * After MethodB: y = 16
		 * 		We are modifying the object 'y' directly. So when it is modified in MethodB, this persists.
		 * 		
		 */
		
	}
	
	public static void helloWorld() {
		out.println("1a)");
		out.println("Hello World!");
	}
	
	public static void FizzBuzz(){
		String response;
		
		out.println("\n1b) FizzBuzz");
		
		for(int i = 1; i <= 200; i++){
			if ((i % 3 == 0) &&
					(i % 5 == 0)){
				response = "FizzBuzz";
			}else if (i % 3 == 0){
				response = "Fizz";
			}else if(i % 5 == 0){
				response = "Buzz";
			}else{
				response = String.valueOf(i);
			}
			out.println(response);
		}
	}
	
	public static void gridStar() {
		out.println("\n2) Grid *");
		
		for (int row = 0; row < 10; row++){
			out.println();
			for (int column = 0; column < 10; column++){
				out.print("*");
			}
		}
	}
	
	public static void triangleStar() {
		out.println("\n\n3) Triangle *");
		
		for (int row = 0; row < 10; row++){
			out.println();
			for (int column = row; column < 10; column++){
				out.print("*");
			}
		}
	}
	
	public static void AnimalInterface(){
		out.println("\n\n4) Animal Interface");
		
		//Create an array with between 1 & 20 animals
		Animal AllAnimals[] = new Animal[randomWithRange(1,20)];
		int random;
		int arrayLength = AllAnimals.length;
		
		out.println("There are " + arrayLength + " animals in the Array:");
		
		//Populate the array
		for (int i = 0; i < arrayLength; i++){
			random = randomWithRange(1,3);
			
			if (random == 1){
				AllAnimals[i] = new Cat();
			}else if (random == 2){
				AllAnimals[i] = new Dog();
			}else {
				AllAnimals[i] = new Cow();
			}
		}
		
		//Print the array
		for (int j = 0; j < arrayLength; j++){
			out.println("Animal " + String.valueOf(j + 1) + " says " + (AllAnimals[j]).Speak());
		}
		
	}
	
	public static void AnimalClass() {

		out.println("\n\n4b) Animal Class");
		
		//Create an array with between 1 & 20 animals
		AnimalClass AllAnimals[] = new AnimalClass[randomWithRange(1,20)];
		int random;
		int arrayLength = AllAnimals.length;
		
		out.println("There are " + arrayLength + " animals in the Array:");
		
		//Populate the array
		for (int i = 0; i < arrayLength; i++){
			random = randomWithRange(1,3);
			
			if (random == 1){
				AllAnimals[i] = new CatClass(randomWithRange(1,14));
			}else if (random == 2){
				AllAnimals[i] = new DogClass(randomWithRange(1,14));
			}else {
				AllAnimals[i] = new CowClass(randomWithRange(1,14));
			}
		}
		//Print the array
		for (int j = 0; j < arrayLength; j++){
			out.println("Animal "
					+ String.valueOf(j + 1)
					+ " is "
					+ String.valueOf(AllAnimals[j].Age)
					+ " years old"
					+ " and says " + (AllAnimals[j]).Speak());
		}
	}
	
	static int randomWithRange(int min, int max)
	{
	   int range = (max - min) + 1;     
	   return (int)(Math.random() * range) + min;
	}
	
}