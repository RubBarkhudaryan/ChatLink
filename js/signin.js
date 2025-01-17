const pass_eye = document.getElementById("show-pass");

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


let signIn = document.getElementById("signIn");

function signInAction() {
    let requestData = {
        action: "signIn",
        username: document.getElementById("username").value,
        password: document.getElementById("password").value,
    };

    let filled = true;
    for (const key in requestData) {
        if (!requestData[key]) {
            filled = false;
        }
    }

    if (filled) {
        $.ajax({
            url: "../php/api.php",
            type: "POST",
            data: JSON.stringify(requestData),
            contentType: "application/json",
            success: function (response) {
                let res = JSON.parse(response);
                if (res.status === "ok") {
                    localStorage.setItem("id", res.id);
                    localStorage.setItem("name", res.name);
                    window.location.href = "../index.php";
                }
                else if (res.msg == "Wrong password") {
                    document.getElementById("error").innerHTML = "Please make sure that you inserted the right password.";
                    document.getElementById("error").style.display = "block";
                }
                else if (res.msg == "Wrong username") {
                    document.getElementById("error").innerHTML = "Please make sure that you inserted the right username.";
                    document.getElementById("error").style.display = "block";
                }
            },
            error: function (error) {
                console.error('Error:', error);
            }
        });
    }
    else {
        document.getElementById("empty-field").style.display = "block";
    }
}

// Add click event listener to sign-in button
signIn.addEventListener("click", signInAction);

// Add keyup event listener to input fields to trigger sign-in on Enter key press
document.getElementById("username").addEventListener("keyup", function (event) {
    if (event.key === "Enter") {
        signInAction();
    }
});

document.getElementById("password").addEventListener("keyup", function (event) {
    if (event.key === "Enter") {
        signInAction();
    }
});

document.querySelector("body").addEventListener("keyup", function (event) {
    if (event.key === "Enter") {
        signInAction();
    }
});