let arr = ['Coding', 'Academy', 'By', 'Orange'];

arr.push("Jordan");
console.log(arr);
console.log(arr[0], arr[1]);
arr.unshift("Welcome");
console.log(arr);
console.log(arr.slice(3, 6));
console.log(arr.join());
console.log(["Coding", "Academy", "By", "Orange"]);
console.log(arr[0], arr[3]);


var fruit = ["banana", "apple", "orange", "watermelon"]; var vegetables = ["carrot", "tomato", "pepper", "lettuce"];

vegetables.pop();
console.log(vegetables);

fruit.shift();
console.log(fruit);
console.log(fruit.indexOf('orange'));

fruit.push(55);
console.log(fruit);

let food = fruit.concat(vegetables);
console.log(food);
food.slice(4,7);
console.log(food);

console.log(food.reverse());

console.log(food.toString());

