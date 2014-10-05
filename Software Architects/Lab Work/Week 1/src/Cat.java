
public class Cat implements Animal {

	String Name = "";
	int Age = 1;
	
	public Cat (){
	}
	public Cat(String name){
		Name = name;
	}
	public Cat(String name, int age){
		Name = name;
		Age = age;
	}
	
	public String Speak(){
		return "Meow!";
	}
}
