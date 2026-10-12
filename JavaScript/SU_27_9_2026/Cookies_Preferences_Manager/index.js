const theme = document.getElementById("theme");
const language = document.getElementById("language");
const body = document.body;


function setCookie(name, value, days) {
    const date = new Date();

    date.setTime(
        date.getTime() + days * 24 * 60 * 60 * 1000
    );

    document.cookie =
        name + "=" + value +
        ";expires=" + date.toUTCString() +
        ";path=/";
}

function getCookie(name) {
    const cookies = document.cookie.split(";");

    for (let cookie of cookies) {
        cookie = cookie.trim();

        if (cookie.startsWith(name + "=")) {
            return cookie.substring(name.length + 1);
        }
    }

    return null;
}

function deleteCookie(name) {
    document.cookie =
        name + "=;expires=Thu, 01 Jan 1970 00:00:00 UTC;path=/";
}


theme.addEventListener("change", function () {
    body.classList.toggle("dark", theme.value === "dark");
    setCookie("theme", theme.value, 30);
});


language.addEventListener("change", function () {
    setCookie("language", language.value, 30);
});

function displayPreferences() {
    const savedTheme = getCookie("theme");
    const savedLanguage = getCookie("language");

    if (savedTheme) {
        theme.value = savedTheme;
    }

    if (savedLanguage) {
        language.value = savedLanguage;
    }

    body.classList.toggle("dark", savedTheme === "dark");
    document.getElementById("saved").innerHTML =
        "Theme: " + savedTheme + "<br>" +
        "Language: " + savedLanguage;
}


displayPreferences();
