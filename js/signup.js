let signUp = document.getElementById("signUp");

function signUpAction() {
    let male = document.getElementById("male");
    let confirmPassword = document.getElementById("confirm-password");

    let requestData = {
        action: "signUp",
        name: document.getElementById("name").value,
        surname: document.getElementById("surname").value,
        username: document.getElementById("username").value,
        password: document.getElementById("password").value,
        age: document.getElementById("age").value,
        email: document.getElementById("email").value,
        phone: document.getElementById("phone").value,
        gender: "",
    }

    if (male.checked) {
        requestData.gender = document.getElementById("male").value;
    }
    else {
        requestData.gender = document.getElementById("female").value;
    }

    let filled = true;
    for (const key in requestData) {
        if (!requestData[key]) {
            filled = false;
        }
    }

    if (!filled) {
        document.getElementById("empty-field").style.display = "block";
        document.getElementById("password-error").style.display = "none";
    }
    else if (confirmPassword.value !== "" && requestData.password !== confirmPassword.value) {
        document.getElementById("password-error").style.display = "block";
        document.getElementById("empty-field").style.display = "none";
    }
    else if (filled) {
        $.ajax({
            url: "/chatlink/php/api.php",
            type: "POST",
            data: JSON.stringify(requestData),
            contentType: "application/json",
            success: function (response) {
                let res = JSON.parse(response);

                if (res.status === "ok") {
                    localStorage.setItem("id", res.id);
                    localStorage.setItem("name", res.name);
                    window.location.href = "/chatlink/php/signin.php";
                }
                else {
                    alert(res.msg);
                }
            },
            error: function (error) {
                console.error('Error:', error);
            }
        });
    }
}

// Add click event listener to sign-up button
signUp.addEventListener("click", signUpAction);

// Add keyup event listeners to relevant input fields to trigger sign-up on Enter key press
document.getElementById("name").addEventListener("keyup", function (event) {
    if (event.key === "Enter") {
        signUpAction();
    }
});

document.getElementById("surname").addEventListener("keyup", function (event) {
    if (event.key === "Enter") {
        signUpAction();
    }
});

document.getElementById("username").addEventListener("keyup", function (event) {
    if (event.key === "Enter") {
        signUpAction();
    }
});

document.getElementById("password").addEventListener("keyup", function (event) {
    if (event.key === "Enter") {
        signUpAction();
    }
});

document.getElementById("age").addEventListener("keyup", function (event) {
    if (event.key === "Enter") {
        signUpAction();
    }
});

document.getElementById("email").addEventListener("keyup", function (event) {
    if (event.key === "Enter") {
        signUpAction();
    }
});

document.getElementById("phone").addEventListener("keyup", function (event) {
    if (event.key === "Enter") {
        signUpAction();
    }
});


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