
const deleteButton = document.getElementById("delete-button");

deleteButton.addEventListener("click", () => {
    let conf = confirm("After confirming your account will be permanently deleted from accounts center. Please check if you downloaded all important data.");

    if (conf) {

        let requestData = {
            action: "deleteAccount",
            user_id: localStorage.getItem("id")
        }

        $.ajax({
            url: "/chatlink/php/api.php",
            type: "POST",
            data: JSON.stringify(requestData),
            contentType: "application/json",

            success: (response) => {
                let result = JSON.parse(response);

                if (result.status) {
                    localStorage.removeItem("id");
                    alert("Your account deleted succesfully. Now you will be redirected to sign in page.");
                    window.location.href = '/chatlink/php/signup.php';
                }
                else {
                    alert("Something went wrong please try later.");
                }
            },
            error: (error) => {
                alert(error);
            }
        })
    }
})