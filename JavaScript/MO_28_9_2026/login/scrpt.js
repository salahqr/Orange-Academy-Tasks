const form = document.getElementById("login-form");
const email = document.getElementById("loginEmail");
const password = document.getElementById("loginPassword");
const msg = document.getElementById("loginMsg");


function seedAdmin() {
    const users = JSON.parse(localStorage.getItem("users")) || [];

    if (!users.some(u => u.role === "admin")) {
        users.push({
            fullName: "Admin",
            email: "admin@example.com",
            password: "admin123",
            address: "HQ",
            role: "admin"
        });
        localStorage.setItem("users", JSON.stringify(users));
    }
}

seedAdmin();

form.addEventListener("submit", function (e) {
    e.preventDefault();

    const emailValue = email.value.trim().toLowerCase();
    const passwordValue = password.value.trim();

    if (emailValue === "") {
        msg.textContent = "Please enter your email.";
        return;
    }
    if (!emailValue.includes("@")) {
        msg.textContent = "Please enter a valid email.";
        return;
    }
    if (passwordValue === "") {
        msg.textContent = "Please enter your password.";
        return;
    }

    const users = JSON.parse(localStorage.getItem("users")) || [];
    const user = users.find(
        u => u.email === emailValue && u.password === passwordValue
    );

    if (!user) {
        msg.textContent = "Incorrect email or password.";
        return;
    }

    const { password: _pw, ...safeUser } = user;
    localStorage.setItem("currentUser", JSON.stringify(safeUser));
      
    window.location.href = "../index.html";
});