
public class Dog implements Animal {

	String Name = "";
	int Age = 1;
	
	public Dog (){
	}
	public Dog(String name){
		Name = name;
	}
	public Dog(String name, int age){
		Name = name;
		Age = age;
	}
	
	public String Speak(){
		return "Woof!";
	}
}
