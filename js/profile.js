followCheck();
blockCheck();

let followUser = document.querySelector(".follow-button");
followUser.addEventListener("click", () => {
    let requestData = {
        action: "followButton",
        follower_id: localStorage.getItem("id"),
        following_id: followUser.getAttribute("data-user-id"),
    };

    $.ajax({
        url: "/chatlink/php/api.php",
        type: "POST",
        data: JSON.stringify(requestData),
        contentType: "application/json",

        success: function (response) {
            let res = JSON.parse(response);

            if (res.status === "following") {
                followUser.innerHTML = "Following";
            } else if (res.status === "not following") {
                followUser.innerHTML = "Follow";
            }
        },

        error: function (error) {
            alert("Something went wrong");
        },
    });
})


let blockUser_ = document.querySelector(".block-user");
blockUser_.addEventListener("click", () => {
    let requestData = {
        action: "blockUser",
        user_id: localStorage.getItem("id"),
        block_user_id: blockUser_.getAttribute("data-user-id"),
    };

    $.ajax({
        url: "/chatlink/php/api.php",
        type: "POST",
        data: JSON.stringify(requestData),
        contentType: "application/json",

        success: function (response) {
            let res = JSON.parse(response);

            if (res.status === "blocked") {
                blockUser_.innerHTML = "Blocked";
            } else if (res.status === "not blocked") {
                blockUser_.innerHTML = "Block";
            }
        },

        error: function (error) {
            alert("Something went wrong");
        },
    });
});

