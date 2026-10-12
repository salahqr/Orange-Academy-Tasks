let ans = "";
for (let index = 0; index < 50; index++) {
    if (index%2 === 0)
        ans += index;
}
let index = 50;
while (index >= 0){
    if (index%2 === 0)
        ans += index;

    index--;
}

console.log(ans);
ans = "";
for (let index = 0; index < 50; index++) {
    if (index%2 === 0)
        ans += index;
}
index = 50;
while (index >= 0){
    if (index%2 !== 0)
        ans += index;

    index--;
}
console.log(ans);

ans = "";
for (let index = 1; index <= 100; index++) {
    if (index%5 === 0 && index%3 === 0) {
        ans+= 'FizzBuzz ';
    } else if (index%5 === 0) {
        ans += 'Buzz ';
    } else if (index%e === 0) {
        ans += 'Fizz ';
    } else {
        ans += index;
    }
}
console.log(ans);
ans = "";
for (let index = 1; index <= 100; index++) {
    FizzBuzz(index);
}
function FizzBuzz(num) {
    if (num % 5 === 0 && num % 3 === 0) {
        ans += 'FizzBuzz ';
    } else if (num % 5 === 0) {
        ans += 'Buzz ';
    } else if (index%num === 0) {
        ans += 'Fizz ';
    } else {
        ans += index;
    }
}
console.log(ans);

ans = "";
function FizzBuzz(num, cur) {
    if (num === 100){
        return num;
    }

    if (num % 5 === 0 && num % 3 === 0) {
        cur += 'FizzBuzz ';
    } else if (num % 5 === 0) {
        cur += 'Buzz ';
    } else if (index%num === 0) {
        cur += 'Fizz ';
    } else {
        cur += index;
    }

    FizzBuzz(num+1);
}
FizzBuzz(0);
console.log(ans);


function banknotes(amount, notes) {
    let result = [];

    for (let note of notes) {
        while (amount >= note) {
            result.push(note);
            amount -= note;
        }
    }

    return result;
}

console.log(banknotes(57, [25, 10, 5, 1]));
function countCharacter(str, char) {
    let count = 0;

    for (let i = 0; i < str.length; i++) {
        if (str[i].toLowerCase() === char.toLowerCase()) {
            count++;
        }
    }

    return count;
}

console.log(countCharacter("Coding Academy by Orange", "o"));

// a. Print numbers 0 - 20
for (let i = 0; i <= 20; i++) {
    console.log(i);
}

// b. Print ODD values from 3 - 29
for (let i = 3; i <= 29; i += 2) {
    console.log(i);
}

// c. Print EVEN numbers 12 down to -14
for (let i = 12; i >= -14; i -= 2) {
    console.log(i);
}

// d. Print multiples of 3 from 50 down to 20
for (let i = 50; i >= 20; i--) {
    if (i % 3 === 0) {
        console.log(i);
    }
}

let str = "CodingAcademy";
let arr = [7, 500, "KH404", "black", 36];

// e. Print each element of the array
for (let i = 0; i < arr.length; i++) {
    console.log(arr[i]);
}

// f. Print each string character in reverse order
for (let i = str.length - 1; i >= 0; i--) {
    console.log(str[i]);
}


let numbers = [7, 23, 18, 9, -13, 38, -10, 12, 0, 124];

let evens = [];
let odds = [];

for (let i = 0; i < numbers.length; i++) {
    if (numbers[i] % 2 === 0) {
        evens.push(numbers[i]);
    } else {
        odds.push(numbers[i]);
    }
}

console.log(evens);
console.log(odds);

let protein = ['chicken', 'pork', 'tofu', 'beef', 'fish', 'beans'];
let grain = ['rice', 'pasta', 'corn', 'potato', 'quinoa', 'crackers'];
let vegetable = ['peas', 'green beans', 'kale', 'edamame', 'broccoli', 'asparagus'];
let beverage = ['juice', 'milk', 'water', 'soy milk', 'soda', 'tea'];
let dessert = ['apple', 'banana', 'more kale', 'ice cream', 'chocolate', 'kiwi'];

function createMeals(number) {
    let meals = [];

    for (let i = 0; i < number; i++) {
        let meal = [
            protein[i % protein.length],
            grain[i % grain.length],
            vegetable[i % vegetable.length],
            beverage[i % beverage.length],
            dessert[i % dessert.length]
        ];

        meals.push(meal);
    }

    return meals;
}

console.log(createMeals(6));