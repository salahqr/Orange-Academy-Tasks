let one = 1000;
let two = 500;

console.log("cash flow ratio " + (one / two));
console.log("net income " + (one - two));
console.log("total assets " + (one + two));
console.log("Net income (using profit margin and sales) " + (one * two));

let a = 7, b = 9, c = 2;

console.log("Average " + ((a + b + c) / 3));

let price = 150;

console.log("Discount " + (price * 0.30));

let year_inp = Number(prompt("Enter any year number = "));

if (year_inp >= 18 && year_inp <= 30) {
    console.log("correct");
} else {
    console.log("wrong");
}

let num1 = Number(prompt("Enter any number = "));
let num2 = Number(prompt("Enter any number = "));

console.log("Exponential " + (num1 ** num2));

let num3 = Number(prompt("Enter any number = "));
let num4 = Number(prompt("Enter any number = "));

console.log("Remainder " + (num3 % num4));