const currentUser = JSON.parse(localStorage.getItem("currentUser"));

if (!currentUser) {
    window.location.href = "./login/index.html";
} else {
    const accountList = document.getElementById("acount_list");

    // User info (shown to everyone)
    const title = document.createElement("h2");
    title.textContent = `Welcome, ${currentUser.fullName}`;
    accountList.appendChild(title);

    [
        ["Email", currentUser.email],
        ["Address", currentUser.address],
        ["Role", currentUser.role]
    ].forEach(([label, value]) => {
        const p = document.createElement("p");
        p.textContent = `${label}: ${value}`;
        accountList.appendChild(p);
    });

    // Admin only: table of all users
    if (currentUser.role === "admin") {
        const users = JSON.parse(localStorage.getItem("users")) || [];

        const heading = document.createElement("h3");
        heading.textContent = "All Registered Users";
        accountList.appendChild(heading);

        const table = document.createElement("table");
        const headRow = document.createElement("tr");
        ["#", "Full Name", "Email", "Address", "Role"].forEach(text => {
            const th = document.createElement("th");
            th.textContent = text;
            headRow.appendChild(th);
        });
        table.appendChild(headRow);

        users.forEach((user, i) => {
            const tr = document.createElement("tr");
            [i + 1, user.fullName, user.email, user.address, user.role].forEach(value => {
                const td = document.createElement("td");
                td.textContent = value;
                tr.appendChild(td);
            });
            table.appendChild(tr);
        });

        accountList.appendChild(table);
    }

    document.getElementById("logout").addEventListener("click", function () {
        localStorage.removeItem("currentUser");
        window.location.href = "../login/index.html";
    });
}