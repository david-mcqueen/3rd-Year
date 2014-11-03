# David McQueen - 10153465

class String

  def frequency
    letters = Hash.new(0)
    self.gsub!(/\W+/, '') # Remove all non letters from the String (! used to update the original)
    self.split(" ").each  do |word|
      word.upcase.split("").each do |letter|
        letters[letter] += 1
      end
    end
    return letters
  end
end

input = "Our deepest fear is not that we are inadequate. Our deepest fear is that we are powerful beyond measure. It is our light not our darkness that most frightens us. We ask ourselves, who am I to be brilliant, gorgeous, talented and fabulous?"
puts "Exercise 1"
puts input.frequency



# Exercise 2 Start

class String

  def histogram
    alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ"
    letters = Hash.new(0)

    # Populate the hash with the full alphabet
    alphabet.split("").each do |letter|
      letters[letter] = 0
    end

    self.gsub!(/\W+/, '')
    self.split(" ").each  do |word|
      word.upcase.split("").each do |letter|
        letters[letter] += 1
      end
    end
    return letters
  end
end


puts "\nExercise 2"
input.histogram.each do |key, value|
  puts "#{key}: " + "*" * value
end

