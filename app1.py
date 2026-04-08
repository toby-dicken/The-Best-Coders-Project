# 1. Even or Odd
def even_or_odd(n):
    return "Even" if n % 2 == 0 else "Odd"

print("1. Even or Odd:", even_or_odd(7))


# 2. Sum of First N Numbers
def sum_n(n):
    total = 0
    for i in range(1, n + 1):
        total += i
    return total

print("2. Sum of first N numbers:", sum_n(5))


# 3. Multiplication Table
def multiplication_table(n):
    for i in range(1, 11):
        print(f"{n} x {i} = {n * i}")

print("\n3. Multiplication Table:")
multiplication_table(3)


# 4. Count Digits
def count_digits(n):
    return len(str(abs(n)))

print("\n4. Count Digits:", count_digits(12345))


# 5. Count Vowels
def count_vowels(s):
    vowels = "aeiouAEIOU"
    count = 0
    for char in s:
        if char in vowels:
            count += 1
    return count

print("\n5. Count Vowels:", count_vowels("Hello World"))


# 6. Find Maximum
def find_max(lst):
    max_val = lst[0]
    for num in lst:
        if num > max_val:
            max_val = num
    return max_val

print("\n6. Maximum Number:", find_max([3, 7, 2, 9, 5]))


# 7. Prime Number Checker
def is_prime(n):
    if n < 2:
        return False
    for i in range(2, n):
        if n % i == 0:
            return False
    return True

print("\n7. Is Prime:", is_prime(11))


# 8. Reverse a Number
def reverse_number(n):
    rev = 0
    while n > 0:
        rev = rev * 10 + (n % 10)
        n //= 10
    return rev

print("\n8. Reverse Number:", reverse_number(123))


# 9. Fibonacci Series
def fibonacci(n):
    a, b = 0, 1
    for _ in range(n):
        print(a, end=" ")

print("\n9. Fibonacci Series:")
fibonacci(7)
print()


# 10. Factorial
def factorial(n):
    result = 1
    for i in range(1, n + 1):
        result *= i
    return result

print("\n10. Factorial:", factorial(5))


# 11. Palindrome Checker
def is_palindrome(s):
    return s == s[::-1]

print("\n11. Is Palindrome:", is_palindrome("madam"))


# 12. Number Guessing Game
# (Commented to avoid infinite loop during testing)
"""
import random

def guessing_game():
    number = random.randint(1, 10)
    while True:
        guess = int(input("Guess the number: "))
        if guess == number:
            print("Correct!")
            break
        else:
            print("Try again!")

# guessing_game()
"""


# 13. Count Even & Odd Numbers in List
def count_even_odd(lst):
    even = odd = 0
    for num in lst:
        if num % 2 == 0:
            even += 1
        else:
            odd += 1
    return even, odd

print("\n13. Even & Odd Count:", count_even_odd([1, 2, 3, 4, 5, 6]))


# 14. Find Second Largest Number
def second_largest(lst):
    first = second = float('-inf')
    for num in lst:
        if num > first:
            second = first
            first = num
        elif num > second and num != first:
            second = num
    return second

print("\n14. Second Largest:", second_largest([10, 20, 5, 8, 20]))


# 15. Pattern Printing
def pattern():
    for i in range(1, 3):
        print("*" * i)
    for i in range(1, 3):
        print("*" * i)

print("\n15. Pattern:")
pattern()