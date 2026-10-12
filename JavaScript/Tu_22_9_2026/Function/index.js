function findSmallest(nums) {
    let min = nums[0];

    for (let i of nums) {
        min = min > i ? i : min;
    }

    return min;
}
console.log(findSmallest([30, 45, 60, 7]));


function AlphabeticalOrder(str) {
    return str.split("").sort().join("");
}
console.log(AlphabeticalOrder("hello")); 

function factorial(num) {
    let ans = 1;

    for (let i = 1; i <= num; i++) {
        ans *= i;
    }

    return ans;
}
console.log(factorial(8)); 


function OddEven(num) {
    return num % 2 === 0 ? "Even" : "Odd";
}
console.log(OddEven(9));


function addUp(num) {
    let ans = 0;

    for (let i = num; i >= 0; i--) {
        ans += i;
    }

    return ans;
}
console.log(addUp(8));


function minMaxLengthAverage(nums) {
    let min = nums[0];
    let max = nums[0];
    let avg = 0;

    for (let i of nums) {
        min = min > i ? i : min;
        max = max < i ? i : max;
        avg += i;
    }

    return [min, max, nums.length, avg / nums.length];
}

console.log(minMaxLengthAverage([7, 13, 3, 77, 100]));


function countWords(str) {
    return str.trim().split(/\s+/).length;
}
console.log(countWords("hello from CodingAcademy!")); // 3


function MultiplyByLength(nums) {
    let length = nums.length;

    for (let i = 0; i < nums.length; i++) {
        nums[i] *= length;
    }

    return nums;
}
console.log(MultiplyByLength([4, 2, 5]));


function checkEnding(str1, str2) {
    return str1.endsWith(str2);
}
console.log(checkEnding("CodingSchool", "Ac"));


function doubleChar(str) {
    let result = "";

    for (let i of str) {
        result += i + i;
    }

    return result;
}
console.log(doubleChar("Coding"));


function findIndex(arr, element) {
    return arr.indexOf(element);
}
console.log(findIndex(
    ["Ali", "Mazen", "Ayham", "Murad"],
    "Ali"
));