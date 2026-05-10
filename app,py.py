def sum_of_evens(start, end):
    total = 0
    for number in range(start, end + 1):
        if number % 2 == 0:
            total += number
    print("Sum of even numbers:", total)