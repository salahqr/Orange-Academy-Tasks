obj = { name: "Adam", age: 25, gender: "male" }; 
for (let i in obj) {
    console.log(obj[i]);
}

obj.major = "CS";
console.log(obj);
console.log(obj.name);

nums = [1, 2, 3, 4, 5, 6]
nums.forEach(element => {
    console.log(element);
});

let fruite = ["apple", "banana", "cherry"];
fruite.sort();
console.log(fruite);

fruite.reverse();
console.log(fruite);

let a = [1, 2, 3], b = [4, 5, 6]; 
let c = a.concat(b, []);
console.log(c);

console.log(c.slice(2,5));
console.log(c.splice(2,22));
console.log(c.indexOf(5));

let str = "1,2,3,4,5";
let numArr = str.split(',');
console.log(numArr);

let joined = numArr.join(',');
console.log(joined);

console.log(numArr.length);
for (let i of numArr) {
    console.log(i);
}

console.log(Array.isArray(numArr));