let unblockUser = document.querySelectorAll(".unblock-user-button");

Array.from(unblockUser).forEach(function (button) {
    button.addEventListener("click", () => {

        let user_id = parseInt(localStorage.getItem("id"));
        let blockedUserUsername = button.closest(".user-profile").querySelector(".username").innerHTML;
        let requestData = {
            action: "unblockUser",
            user_id: user_id,
            block_user_id: blockedUserUsername
        };

        let res = confirm("Are you sure you want to unblock this user?");

        if (res) {
            $.ajax({
                url: "/chatlink/php/api.php",
                type: "POST",
                data: JSON.stringify(requestData),
                contentType: "application/json",

                success: function (response) {
                    let res = JSON.parse(response);

                    if (res.status === "unblocked") {
                        button.closest(".user-profile").remove();
                    } else if (res.status === "not unblocked") {
                        alert("Something went wrong please try later");
                    }
                },

                error: function (error) {
                    alert("Something went wrong");
                },
            });
        }
    });
});
