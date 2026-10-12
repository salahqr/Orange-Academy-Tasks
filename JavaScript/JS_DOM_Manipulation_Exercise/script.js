/* ============================================================
   EXERCISE: MISSION CHECKLIST
   Build a working to-do app for the Space Explorer crew.

   WHAT THE FINISHED APP DOES:
   1. Shows the starting tasks from the data below
   2. Adds a new task when the form is submitted
   3. Marks a task as done (or not done) when you click its text
   4. Deletes a task when you click its Delete button
   5. Shows how many tasks are still remaining
   6. Shows a message when the list is completely empty
   7. Clears all completed tasks with one button

   WHAT YOU WILL PRACTICE:
   objects and arrays of objects, loops, functions, selecting
   elements, createElement, appendChild, classList, dataset,
   addEventListener, event.target, and preventDefault.

   HOW TO WORK:
   Go step by step, in order. Each step has a TODO, some hints,
   and a CHECKPOINT. Do not move on until the checkpoint works.
   Keep the browser console open (F12) to catch errors early.

   THE BIG IDEA:
   The tasks ARRAY is the single source of truth. We never edit
   the list on the page directly. Instead we:
   change the array  ->  call renderTasks()  ->  page redraws
   ============================================================ */


/* ===== THE DATA (given) =====
   Each task is an object with three keys:
   id    a unique number, so we can tell tasks apart
   text  what the task says
   done  true if completed, false if not */

let tasks = [
  { id: 1, text: "Check the rover battery", done: false },
  { id: 2, text: "Review the Mars landing map", done: true },
  { id: 3, text: "Brief Rania on the launch plan", done: false }
];

// The id the NEXT new task will get. Increase it after every add.
let nextId = 4;


/* ============================================================
   STEP 1: SELECT THE ELEMENTS
   TODO: Store each of these elements in a const variable.

   IDs you need:
   task-form, task-input, task-list, counter, empty-msg, clear-done

   Hint: document.getElementById("...")
   CHECKPOINT: console.log one of them, you should see the element,
   not null. If you see null, check the spelling of the id.
   ============================================================ */

// your code here
const task_from = document.getElementById("task-form");
const task_input = document.getElementById("task-input");
const task_list = document.getElementById("task-list");
const counter = document.getElementById("counter");
const empty_msg = document.getElementById("empty-msg");
const clear_done = document.getElementById("clear-done");
const char_count = document.getElementById("char-count");

/* ============================================================
   STEP 2: WRITE THE renderTasks() FUNCTION
   This function draws the whole list from the tasks array.

   TODO inside the function:
   a) Empty the list first, so we don't duplicate items:
      list.innerHTML = "";
   b) Loop over the tasks array (for...of works well here)
   c) For EACH task, create this structure:

      <li data-id="1">
        <span class="task-text">Check the rover battery</span>
        <button class="delete-btn">Delete</button>
      </li>

      Hints:
      document.createElement("li")
      li.dataset.id = task.id
      span.textContent = task.text
      span.classList.add("task-text")
      li.appendChild(span)

   d) If task.done is true, add the class "done" to the li
   e) Append the li to the list
   f) At the very end, call updateCounter() (you write it in step 4)

   CHECKPOINT: after step 3, you should see the 3 starting tasks,
   and the second one should appear crossed out.
   ============================================================ */

let filter = 'all';

function renderTasks() {
    task_list.innerHTML = "";

    tasks.forEach((task) => {
      if (filter == "done" && task.done === false) {return;}
      if (filter == "active" && task.done === true) {return;}

      const li = document.createElement('li');
      li.dataset.id = task.id;

      const span = document.createElement('span');
      span.textContent = task.text;
      span.classList.add('task-text');

      const button = document.createElement('button');
      button.textContent = 'remove';
      button.classList.add('delete-btn');

      li.appendChild(span);
      li.appendChild(button);

      if (task.done === true) {
         li.classList.add('done');
      }

      task_list.appendChild(li);
    });

    updateCounter();
}

/* ============================================================
   STEP 3: CALL renderTasks() ON PAGE LOAD
   TODO: Scroll to the very bottom of this file and call the
   function there, so the list appears when the page opens.
   ============================================================ */


/* ============================================================
   STEP 4: WRITE THE updateCounter() FUNCTION
   TODO:
   a) Count how many tasks have done === false
      Hint: start a variable at 0, loop, add 1 when not done
   b) Write the result into the counter element:
      "2 task(s) remaining"
   c) If tasks.length is 0, REMOVE the "hidden" class from the
      empty message. Otherwise ADD the "hidden" class.

   CHECKPOINT: the footer should say "2 task(s) remaining".
   ============================================================ */

function updateCounter() {
  // your code here
  if (tasks.length === 0) {
   empty_msg.classList.remove('hidden');
  } else {
      empty_msg.classList.add('hidden');
  }

  let conunt = 0;

  for (let i of tasks) {
      if (i.done === false) conunt++;
  }

  counter.innerText = conunt + "  task(s) remaining";
}


/* ============================================================
   STEP 5: ADD A NEW TASK WITH THE FORM
   TODO: Listen for the "submit" event on the form.
   Inside the listener:
   a) Stop the page from reloading: event.preventDefault()
   b) Read the input value and trim the spaces
   c) If the text is empty, stop with return
   d) Create a new task OBJECT using nextId, the text, done: false
   e) Add it to the array: tasks.push(newTask)
   f) Increase nextId by 1
   g) Clear the input
   h) Call renderTasks()

   CHECKPOINT: type a task, press Add, it appears at the bottom
   and the counter goes up by 1. Empty input adds nothing.
   ============================================================ */

// your code here
document.querySelector('#task-form').addEventListener("submit", function(event) {
   event.preventDefault();
   const input = document.querySelector('#task-input');
   const text = input.value.trim();

   if (text.length === 0) return;

   for (let task of tasks) {
      if (task.text.toLowerCase() === text.toLowerCase()) {
         char_count.textContent = 'NO DUPLICATES';
         char_count.style.color = 'red';
         task_input.value = "";
         return;
      }
   }

   const newObj = {id : nextId, text : text, done: false}
   nextId++;

   tasks.push(newObj);
   task_input.value = "";

   char_count.textContent = '0 / 50';
   renderTasks();
});

/* ============================================================
   STEP 6: TOGGLE DONE AND DELETE (event delegation)
   Instead of one listener per task, put ONE "click" listener on
   the list itself and check what was clicked with event.target.

   Important: dataset values are always STRINGS.
   Convert with Number(): Number(li.dataset.id)

   TODO inside the listener:
   a) Get the clicked element: const target = event.target
   b) Get the li it belongs to: target.parentElement
      and read its id with Number(...dataset.id)

   c) If target has the class "task-text" (TOGGLE):
      loop over tasks, find the one with the matching id,
      and flip its value: task.done = !task.done
      Then call renderTasks()

   d) If target has the class "delete-btn" (DELETE):
      build a NEW empty array, loop over tasks, and push every
      task EXCEPT the one with the matching id. Then replace:
      tasks = newArray
      Then call renderTasks()

   Hint: target.classList.contains("task-text") returns true/false

   CHECKPOINT: clicking text crosses it out and back again,
   Delete removes the task, the counter updates every time.
   ============================================================ */

// your code here
task_list.addEventListener('click', function(event) {
   const clicked = event.target;
   const li = clicked.closest('li');
   const id = Number(li.dataset.id);

    if (clicked.tagName === 'SPAN') {
      for (let task of tasks) {
         if (task.id === id) {
               task.done = !task.done;
         }
      }
   } else  if (clicked.tagName === 'BUTTON'){
      for (let index = 0; index < tasks.length; index++) {
         if (tasks[index].id === id) {
            tasks.splice(index, 1);
            break;
         }        
      }
   }

   renderTasks();
});

/* ============================================================
   STEP 7: CLEAR COMPLETED TASKS
   TODO: Listen for "click" on the clear-done button.
   Build a new array that keeps ONLY tasks where done is false,
   replace tasks with it, and call renderTasks().

   CHECKPOINT: mark two tasks done, click "Clear completed",
   both disappear. Delete everything and the empty message shows.
   ============================================================ */

// your code here
clear_done.addEventListener('click', function(event){
   const new_list = [];
   for (let index = 0; index < tasks.length; index++) {
      if (tasks[index].done !== true) {
         new_list.push(tasks[index]);
      }        
   }

   tasks = new_list;
   renderTasks();
});

/* ============================================================
   BONUS CHALLENGES (for those who finish early)

   BONUS 1: LIVE CHARACTER COUNTER
   Listen for the "input" event on the text field and update
   #char-count to show "12 / 50" as the user types.
   Remember to reset it to "0 / 50" after adding a task.

   BONUS 2: NO DUPLICATES
   Before adding a task, loop over tasks and check if the same
   text already exists (ignore upper/lower case with
   .toLowerCase()). If it does, don't add it.

   BONUS 3: FILTER BUTTONS (All / Active / Done)
   a) Create a variable: let currentFilter = "all";
   b) Select all buttons with the class "filter-btn"
      (querySelectorAll) and add a click listener to each
   c) On click: set currentFilter to the button's data-filter,
      move the "active" class to the clicked button, re-render
   d) In renderTasks, skip tasks that don't match the filter:
      "active" shows only not-done tasks
      "done"   shows only done tasks
      Hint: continue skips the current loop round
   ============================================================ */
task_input.addEventListener('input', function(event){
   char_count.style.color='#9a9ac0';
   char_count.textContent = task_input.value.length + ' / 50';
});

const filter_buttons = document.querySelectorAll('.filter-btn');
filter_buttons.forEach(function(button) {

    button.addEventListener('click', function(event) {

        const clicked = event.target;

        filter = clicked.dataset.filter;

        for (let button of filter_buttons) {
            button.classList.remove('active');
        }

        clicked.classList.add('active');

        renderTasks();
    });

});
/* ===== STEP 3 GOES HERE: call renderTasks() ===== */
renderTasks();