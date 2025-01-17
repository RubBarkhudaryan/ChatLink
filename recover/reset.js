const pass_eye = document.getElementById("show-pass");
const conf_pass_eye = document.getElementById("show-conf-pass");

pass_eye.addEventListener("click", (event) => {
    event.preventDefault();
    const password = document.getElementById("password");

    if (password.type === "password") {
        password.type = "text";
        pass_eye.innerHTML = `<i class="fa-solid fa-eye-slash"></i>`;
    }
    else {
        password.type = "password";
        pass_eye.innerHTML = `<i class="fa-solid fa-eye"></i>`;
    }
});

conf_pass_eye.addEventListener("click", (event) => {
    event.preventDefault();
    const confirmPassword = document.getElementById("confirm-password");

    if (confirmPassword.type === "password") {
        confirmPassword.type = "text";
        conf_pass_eye.innerHTML = `<i class="fa-solid fa-eye-slash"></i>`;
    }
    else {
        confirmPassword.type = "password";
        conf_pass_eye.innerHTML = `<i class="fa-solid fa-eye"></i>`;
    }
})