let currentStep = sessionStorage.getItem("step") || 1;

function showStep() {
    document.getElementById("step1").style.display = "none";
    document.getElementById("step2").style.display = "none";
    document.getElementById("step3").style.display = "none";
    document.getElementById("step" + currentStep).style.display = "block";

    sessionStorage.setItem("step", currentStep);
    if (currentStep == 3) {
        showReview();
    }
}

function nextStep() {
    saveData();

    if (currentStep < 3) {
        currentStep++;
        showStep();
    }
}

function previousStep() {
    if (currentStep > 1) {
        currentStep--;
        showStep();
    }
}

function saveData() {
    sessionStorage.setItem( "name", document.getElementById("name").value );

    sessionStorage.setItem(
        "age",
        document.getElementById("age").value
    );

    sessionStorage.setItem(
        "university",
        document.getElementById("university").value
    );

    sessionStorage.setItem(
        "major",
        document.getElementById("major").value
    );
}

function showReview() {
    document.getElementById("review").innerHTML = `
        Name: ${sessionStorage.getItem("name")} <br>
        Age: ${sessionStorage.getItem("age")} <br>
        University: ${sessionStorage.getItem("university")} <br>
        Major: ${sessionStorage.getItem("major")}
    `;
}

function confirm() {
    alert("Registration confirmed!");
    sessionStorage.clear();
    currentStep = 1;
    showStep();
}

showStep();