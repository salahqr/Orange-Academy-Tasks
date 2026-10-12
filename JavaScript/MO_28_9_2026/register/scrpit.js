const form = document.getElementById("register-form");

form.addEventListener("submit", function (e) {
    e.preventDefault();

    const fullName = document.getElementById("regFullName").value.trim();
    const email = document.getElementById("regEmail").value.trim().toLowerCase();
    const password = document.getElementById("regPassword").value.trim();
    const address = document.getElementById("regAddress").value.trim();
    const confirmPassword = document.getElementById("regConfirmPassword").value.trim();

    if (fullName === "") {
        alert("Please enter your full name");
        return;
    }
    if (email === "" || !email.includes("@")) {
        alert("Please enter a valid email");
        return;
    }
    if (password.length < 6) {
        alert("Password must be at least 6 characters");
        return;
    }
    if (address === "") {
        alert("Please enter your address");
        return;
    }
    if (password !== confirmPassword) {
        alert("Passwords do not match");
        return;
    }

    const users = JSON.parse(localStorage.getItem("users")) || [];

    if (users.some(u => u.email === email)) {
        alert("This email is already registered");
        return;
    }

    users.push({ fullName, email, password, address, role: "user" });
    localStorage.setItem("users", JSON.stringify(users));

    const { password: _pw, ...safeUser } = users[users.length - 1];
    localStorage.setItem("currentUser", JSON.stringify(safeUser));

    alert("Registration successful!");
    window.location.href = "../index.html";
});