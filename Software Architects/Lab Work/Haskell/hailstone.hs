hailstone :: Int -> [Int] --accepts an int. Returns a list of Ints
hailstone 1 = [1] --Stopping condition. If start with 1, then the list is 1
hailstone x = if ((x `mod` 2) == 0)
		--Recursively call hailstone, building up a list as it goes along.
			then x : (hailstone (x `div` 2)) 
			else x : (hailstone (x * 3 + 1))

hailstoneSeq x = map length(map hailstone x)