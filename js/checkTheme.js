function onload() {
    let localS = localStorage.getItem("theme");
    let themeToSet = localS;

    if (!localS) {
        themeToSet = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
        document.documentElement.setAttribute("data-theme", themeToSet);
    } else {
        document.documentElement.setAttribute("data-theme", localS);
    }
}

onload();