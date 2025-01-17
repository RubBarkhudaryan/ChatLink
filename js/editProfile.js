let editProfile = document.getElementById("updateData");

editProfile.addEventListener("click", () => {

    let male = document.getElementById("male");
    let gender_ = "";
    if (male.checked) {
        gender_ = "male";
    }
    else {
        gender_ = "female";
    }

    let requestData = {
        action: "updateData",
        id: parseInt(localStorage.getItem("id")),
        name: document.getElementById("name").value,
        surname: document.getElementById("surname").value,
        username: document.getElementById("username").value,
        bio: document.getElementById("bio").value,
        gender: gender_,
    };

    let filled = true;
    for (const key in requestData) {
        if (!requestData[key]) {
            filled = false;
            break;
        }
    }

    if (!filled) {
        alert("Insert all fields");
    }
    else if (filled) {
        $.ajax({
            url: "../php/api.php",
            type: "POST",
            data: JSON.stringify(requestData),
            contentType: "application/json",
            success: function (response) {
                let res = JSON.parse(response);
                if (res.status === "ok") {
                    alert(res.message)
                }
                else if (res.status === "error") {
                    alert(res.message);
                }
            },
            error: function () {
                alert('Error:');
            },
        });
    }

});


