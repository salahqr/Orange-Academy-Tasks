
const form = document.getElementById("task-form");
const taskInput = document.getElementById("task-input");
const showTask = document.getElementById("show_task");
const deleteAll = document.getElementById("delete_all_taks");

let tasks = JSON.parse(localStorage.getItem("tasks")) || [];

function displayTasks() {
    showTask.innerHTML = "";

    tasks.forEach((task, index) => {
        showTask.innerHTML += `
            <p>
                ${task.name}
                <button onclick="completeTask(${index})">
                    ${task.completed ? "Undo" : "Complete"}
                </button>
                <button onclick="deleteTask(${index})">
                    Delete
                </button>
            </p>
        `;
    });

    showTask.innerHTML += `<p>Total tasks: ${tasks.length}</p>`;
}

form.addEventListener("submit", function (event) {
    event.preventDefault();

    const input = taskInput.value.trim();

    if (input === "") return;

    tasks.push({
        name: input,
        completed: false
    });

    localStorage.setItem("tasks", JSON.stringify(tasks));
    taskInput.value = "";
    displayTasks();
});

function completeTask(index) {
    tasks[index].completed = !tasks[index].completed;
    localStorage.setItem("tasks", JSON.stringify(tasks));
    displayTasks();
}

function deleteTask(index) {
    tasks.splice(index, 1);
    localStorage.setItem("tasks", JSON.stringify(tasks));
    displayTasks();
}

deleteAll.addEventListener("click", function () {
    tasks = [];
    localStorage.removeItem("tasks");
    displayTasks();
});

displayTasks();
