
def choose_operation(choice):
    if choice == "add":
        def add_ten(n):
            return n + 10
        return add_ten
    
    elif choice == "subtract":
        def subtract_ten(n):
            return n - 10
        return subtract_ten

operation = choose_operation("add")

result = operation(5)
print(result)   # Expected: 15



operation2 = choose_operation("subtract")
result2 = operation2(5)
print(result2)  # Expected: -5