let year_inp = Number(prompt("Enter any year number = "));
year_inp -= 2026;

if (year_inp > 60) {
    console.log("You may join the seniors’ program");
} else if (year_inp > 30){
    console.loga("You are not eligible. You may join other programs");
} else if (year_inp < 18 ) {
    console.log("You may join the kids' program.");
} else {
    console.log("You are eligible. Start your application");
}

function camelCase(str) {
    return str.split(" ").map(word => word[0].toUpperCase() + word.slice(1)).join("");
}

console.log(camelCase("Coding Academy by Orange"));

function removeElement(arr, element) {
    return arr.filter(item => item !== element);
}

console.log(removeElement(["Coding", "Academy", "By", "Orange"], "By"));


function oddOrEven(num) {
    return num % 2 === 0 ? "Even" : "Odd";
}

console.log(oddOrEven(5));

function isNumber(value) {
    return typeof value === "number";
}

console.log(isNumber(10));

function largest(a, b) {
    return a > b ? a : b;
}

console.log(largest(10, 20));

function triangleType(a, b, c) {
    if (a === b && b === c) {
        return "Equilateral";
    } else if (a === b || a === c || b === c) {
        return "Isosceles";
    } else {
        return "Scalene";
    }
}

console.log(triangleType(5, 5, 5));

function inRange(num, min, max) {
    return num >= min && num <= max;
}

console.log(inRange(5, 1, 10));

function isLeapYear(year) {
    return year % 4 === 0 && (year % 100 !== 0 || year % 400 === 0);
}

console.log(isLeapYear(2024));