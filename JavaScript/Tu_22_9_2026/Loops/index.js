let ans = "";

let i = 1;
while (i <= 10) {
    ans += i + " ";
    i++;
}
console.log(ans);

let nums = [1,2,3,4,5];
for (let i of nums) {
    console.log(i);
}

for (let index = 0; index <= 10; index++) {
    if (index % 2 == 0) {
        console.log(index);
    }
}

let sum = 0;
for (let index = 1; index <= 10; index++) {
   sum += index;
}
console.log(sum);

let max = nums[0];
for (let i of nums) {
   max = (max > i ? max : i);
}
console.log(max);

sum = 0;
for (let i of nums) {
    sum += i;
}
console.log(sum / nums.length);

sum = 1;
for (let index = 1; index <= 5; index++) {
   sum *= index;
}
console.log(sum);

let num = 10;
let a = 0;
let b = 1;

for (let i = 0; a <= num; i++) {
    console.log(a);
    let next = a + b;
    a = b;
    b = next;
}

num = 20;
for (let i = 2; i <= num; i++) {
    let isPrime = true;

    for (let j = 2; j < i; j++) {
        if (i % j === 0) {
            isPrime = false;
            break;
        }
    }

    if (isPrime) {
        console.log(i);
    }
}

let numss = [[1, 2, 3], [4, 5, 6], [7, 8, 9]];
numss.forEach(element => {
    for (let i of element) {
        console.log(i);
    }
});

ans = "";
for (let index = nums.length - 1; index >= 0; index--) {
    ans += nums[index];
}
console.log(ans);

function printWithStep(arr, step) {
    let out = "";
    for (let index = 0; index < arr.length; index += step) {
        out += arr[index] + " ";
    }
    console.log(out);
}
printWithStep(nums, 2);

function countFrequency(arr, target) {
    let count = 0;
    for (let i of arr) {
        if (i === target) count++;
    }
    return count;
}
console.log(countFrequency([1, 2, 1, 3, 2, 1], 1));

const heros = [
    {name: 'Iron Man', power: 'Tech'},
    {name: 'Spider-Man', power: 'Spider abilities'},
    {name: 'Thor', power: 'Godly powers'},
    {name: 'Hulk', power: 'Super strength'}
];

const newHeros = heros.map((element, index) => ({
    hero: element.name,
    power: element.power,
    id: index,
}));
console.log(newHeros);

const inputWords = ["spray", "limit", "elite", "exuberant", "destruction", "present"];

function output(input) {
    return input.filter(word => word.length > 7);
}
console.log(output(inputWords));

const numbers = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
let result = numbers.reduce((sum, num) => {
    if (num % 5 === 0) {
        return sum + num ** 2;
    }
    return sum;
}, 0);

console.log(result);