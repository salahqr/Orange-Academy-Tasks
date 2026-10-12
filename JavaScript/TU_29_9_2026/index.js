const STORAGE_KEY = "employees";

function Employee(name, email, department) {
    this.name = name;
    this.email = email;
    this.department = department;
    this.salary = Math.floor(Math.random() * (1000 - 100 + 1)) + 100; 
}

let employees = JSON.parse(localStorage.getItem(STORAGE_KEY)) || [];

function saveEmployees() {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(employees));
}

function render() {
    const tbody = document.getElementById("tableBody");
    tbody.innerHTML = "";

    employees.forEach(function(emp){
        const row = document.createElement("tr");
        
        for (let value of Object.values(emp)) {
            const cell = document.createElement("td");
            cell.textContent = value;
            row.appendChild(cell);
        }
        tbody.appendChild(row);
    });

    const total = employees.reduce(function (sum, emp) {
    return sum + emp.salary;
    }, 0);
    document.getElementById("total").textContent = "Total Salary: " + total;
}

document.getElementById("employeeForm").addEventListener("submit", function (event) {
    event.preventDefault();

    const name = document.getElementById("name").value.trim();
    const email = document.getElementById("email").value.trim();
    const department = document.getElementById("department").value.trim();

    const employee = new Employee(name, email, department);
    employees.push(employee);

    saveEmployees();
    render();
    event.target.reset();
});

render();