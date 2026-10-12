let str = 'welcome to orange';

console.log(str.toUpperCase());
console.log(str.substring(8,10));
console.log(str.split(" ").map(word => word[0].toUpperCase() + word.slice(1)).join(" "));console.log(str.toLowerCase());
console.log(str.length);

str = "welcome to 'orange'";
console.log(str);

str += " Jordan";
console.log(str);

let word = prompt("Enter a string:");
let firstLetter = word[0];
let result = word.replaceAll(firstLetter, "*");
console.log(result);