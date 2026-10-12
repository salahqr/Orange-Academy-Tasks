import students, { getStudents, getStudentById } from "./students.js";
import { calculateAverage, getStatus } from "./grades.js";

// =====================================================
console.log("Exercise 1");
console.log(name); // undifay
var name = "Jone";
function test() {
 var x = 10;
 if (true) {
 var y = 20;
 }
 console.log(y); // 20
}
test();

console.log("Predict the original output: name = undefined & y = 20");
console.log("make it with let");

let Name = 'Jone';
console.log(Name);

function test() {
        let x = 10;
    if (true) {
        let y = 20;
           console.log(y);
    }
}
test();

// =====================================================
console.log("Exercise 2");

function Person(name, age) {
    this.name = name;
    this.age = age;
}
Person.prototype.greet = function () {
    console.log("Hello, my name is " + this.name + " and I am " + this.age + " years old.");
};

function Employee(name, age, employeeId, position) {
    Person.call(this, name, age);

    this.employeeId = employeeId;
    this.position = position;
}
Employee.prototype = Object.create(Person.prototype);
Employee.prototype.constructor = Employee;
Employee.prototype.greet = function () {
    console.log(
        "Hello, my name is " + this.name +
        ". I am a " + this.position +
        " and my employee ID is " + this.employeeId + "."
    );
};

var employee1 = new Employee("John", 25, 101, "Software Engineer");
var employee2 = new Employee("Sara", 28, 102, "Backend Developer");
var employee3 = new Employee("Ali", 24, 103, "Frontend Developer");

employee1.greet();
employee2.greet();
employee3.greet();
console.log(employee1.name);      
console.log(employee1.age);       
console.log(employee1.employeeId);
console.log(employee1.position);  

// =====================================================
console.log("Exercise 3");

const students1 = [];
const students2 = [];

for (let i = 0; i < 50; i++) {
    if (i < 25) {
        students1.push(`studentNumber_${i}`);
    } else {
        students2.push(`studentNumber_${i}`);
    }
}

const students = students1.concat(students2);
students.sort();
students.reverse();
console.log(students.includes("studentNumber_20"));
students.forEach(function (student, index) {
    console.log(index, student);
});

// =====================================================
console.log("Exercise 4");

const studentss = [];

for (let i = 0; i < 50; i++) {
    studentss.push({
        id: i + 1,
        name: `studentNumber_${i}`,
        grade: Math.floor(Math.random() * 101)
    });
}

studentss.splice(5, 0, {
    id: 51,
    name: "NewStudent",
    grade: 85
});

studentss.splice(10, 1);
studentss.splice(15, 1, {
    id: 52,
    name: "ReplacementStudent",
    grade: 95
});

const copiedStudents = studentss.slice(0, 10);
console.log(copiedStudents);
studentss.sort(function (a, b) {
    return b.grade - a.grade;
});
studentss.forEach(function (student) {
    console.log(
        student.id,
        student.name,
        student.grade
    );
});


// =====================================================
console.log("Exercise 5");

const product = {
    id: 1,
    name: "Laptop",
    price: 500,
    category: "Electronics",
    available: true
};

const jsonProduct = JSON.stringify(product);
console.log(product);
console.log(jsonProduct);

const convertedProduct = JSON.parse(jsonProduct);
console.log(convertedProduct);

try {
    const invalidProduct = JSON.parse('{"name": "Laptop"');
    console.log(invalidProduct);
} catch (error) {
    console.log("Invalid JSON");
}

// =====================================================
console.log("Exercise 6");

const inventory1 = [];
for (let i = 1; i <= 5; i++) {
    inventory1.push({
        id: i,
        name: `Product_${i}`,
        price: i * 100,
        category: "Electronics",
        quantity: i + 5
    });
}

const inventory2 = [];
for (let i = 6; i <= 10; i++) {
    inventory2.push({
        id: i,
        name: `Product_${i}`,
        price: i * 100,
        category: "Clothing",
        quantity: i + 5
    });
}

const inventory = inventory1.concat(inventory2);
console.log(inventory);

inventory.sort(function (a, b) {
    return a.price - b.price;
});


const categories = ["Electronics", "Clothing", "Food"];

console.log(categories.includes("Electronics")); 
console.log(categories.includes("Food"));       

inventory.splice(2, 1);
const firstFive = inventory.slice(0, 5);
console.log(firstFive);

inventory.forEach(function (product) {
    console.log(
        product.id,
        product.name,
        product.price,
        product.category,
        product.quantity
    );
});


// =====================================================
console.log("Exercise 7");

const square = (num) => {
    return num * num;
};

const isEven = (num) => {
    return num % 2 === 0;
};

const totalPrice = (price, quantity) => {
    return price * quantity;
};

console.log(square(5));          
console.log(isEven(10));         
console.log(totalPrice(50, 3));  

// =====================================================
console.log("Exercise 8");

const user = {
    name: "name",
    email: "name@gmail.com",
    age: 25,
    address: "Amman Jordan",
    skills: ["java", "c++", "js", "ts"]
};
const { name, age: userAge, email, address } = user;
const [first, second, ...third] = user.skills;

function createUser(email, name = "user2", age = 20) {
    return {
        name: name,
        email: email,
        age: age
    };
}

console.log(createUser("ddd@gmail.com"));
console.log(createUser("ddd@gmail.com", "sss", 33));


// =====================================================
console.log("Exercise 9");

// Two arrays of enrolled students
const students3 = [101, 102, 103, 104, 105];
const students4 = [103, 104, 105, 106, 107];
const enrolledStudents = [...students3, ...students4];

console.log(enrolledStudents);
const uniqueStudents = [...new Set(enrolledStudents)];
console.log(uniqueStudents);

const average = (...grades) => {
    let sum = 0;

    for (let grade of grades) {
        sum += grade;
    }

    return sum / grades.length;
};

console.log(average(80, 90, 70));
const studentGrades = new Map();

studentGrades.set(101, 80);
studentGrades.set(102, 90);
studentGrades.set(103, 75);
studentGrades.set(104, 88);
studentGrades.set(105, 95);
studentGrades.set(106, 70);
studentGrades.set(107, 85);
studentGrades.set(101, 85);
console.log(studentGrades.get(101));

studentGrades.delete(107);
const finalStudents = [...studentGrades];
console.log(finalStudents);

// =====================================================
console.log("Exercise 10");

const students5 = [
    { id: 1, name: "Ahmad", grade: 85 },
    { id: 2, name: "Sara", grade: 72 },
    { id: 3, name: "Omar", grade: 45 },
    { id: 4, name: "Lina", grade: 91 },
    { id: 5, name: "Ali", grade: 55 }
];
const reports = document.getElementById("reports");

students.forEach((student) => {
    const status = student.grade >= 50 ? "Pass" : "Fail";
    const report = `
        <div class="student-report">
            <h2>Student Report</h2>
            <p>Name: ${student.name}</p>
            <p>ID: ${student.id}</p>
            <p>Grade: ${student.grade}</p>
            <p>Status: ${status}</p>
        </div>
    `;
    reports.innerHTML += report;
});


// =====================================================
console.log("Exercise 11");

class Person {
    constructor(name, email) {
        this.name = name;
        this.email = email;
    }

    getInfo() {
        return `Your account name is ${this.name} and email = ${this.email}`;
    }
}

class Student extends Person {
    constructor(name, email, studentId) {
        super(name, email);

        this.studentId = studentId;
    }

    getInfo() {
        return `Student: ${this.name}, Email: ${this.email}, ID: ${this.studentId}`;
    }
}


class Instructor extends Person {
    constructor(name, email, subject) {
        super(name, email);

        this.subject = subject;
    }

    getInfo() {
        return `Instructor: ${this.name}, Email: ${this.email}, Subject: ${this.subject}`;
    }
}
const person = new Person("Ahmad", "ahmad@gmail.com");
const student = new Student("Ali", "ali@gmail.com", 101 );

const instructor = new Instructor("Sara", "sara@gmail.com", "JavaScript");

console.log(person.getInfo());
console.log(student.getInfo());
console.log(instructor.getInfo());

//=====================================================
console.log("Exercise 12");

const studentsList = document.getElementById("students");
const allStudents = getStudents();

allStudents.forEach(student => {

    const average = calculateAverage(student.grades);
    const status = getStatus(average);

    studentsList.innerHTML += `
        <div>
            <h2>${student.name}</h2>
            <p>ID: ${student.id}</p>
            <p>Average: ${average.toFixed(2)}</p>
            <p>Status: ${status}</p>
        </div>
    `;
});

// =====================================================
console.log("Exercise 13");

localStorage.setItem("name", "Ahmad");
localStorage.getItem("name");
localStorage.removeItem("name");
localStorage.clear();
localStorage.key(0);
localStorage.length;

// =====================================================
console.log("Exercise 14");
console.log("you can see the solve in folder to_do_task");

// =====================================================
console.log("Exercise 15");
console.log("you can see the solve in folder Multi_Step_Form");

// =====================================================
console.log("Exercise 16");
console.log("you can see the solve in folder Cookies_Preferences_Manager");
