
public class Cow implements Animal{
	
	String Name = "";
	int Age = 1;
	
	public Cow (){
	}
	public Cow(String name){
		Name = name;
	}
	public Cow(String name, int age){
		Name = name;
		Age = age;
	}
	
	public String Speak(){
		return "Moo!";
	}
	
}
