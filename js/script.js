//AJAX for sign out
let signOut = document.getElementById("signOut");

signOut.addEventListener("click", () => {
  $(document).ready(function () {
    let requestData = {
      action: "signOut",
    };

    $.ajax({
      url: "/chatlink/php/api.php",
      type: "POST",
      data: JSON.stringify(requestData),
      contentType: "application/json",

      success: function (response) {
        let res = JSON.parse(response);
        if (res.status) {
          window.location.href = "/chatlink/php/signin.php";
        }
      },
      error: function (error) {
        alert("An error occured while signing out. Try again later.");
      },
    });
  });
});

//AJAX for likes
let likeButton = document.getElementsByClassName("like-button");

Array.from(likeButton).forEach(function (button) {
  button.addEventListener("click", () => {
    let userID = button.getAttribute("data-user-id");
    let postID = button.getAttribute("data-post-id");
    let likesCount = button.closest(".post").querySelector(".likes-count");

    $(document).ready(function () {
      let requestData = {
        action: "addLike",
        user_id: userID,
        post_id: postID,
      };

      $.ajax({
        url: "/chatlink/php/api.php",
        type: "POST",
        data: JSON.stringify(requestData),
        contentType: "application/json",

        success: function (response) {
          let res = JSON.parse(response);

          if (res.status === "success") {
            let currentLikes = res.count;
            likesCount.innerHTML = parseInt(currentLikes) + " Likes";

            if (res.liked) {
              button.innerHTML = "<i class='fa-solid fa-heart'></i>"
            }
            else {
              button.innerHTML = "<i class='fa-regular fa-heart'></i>"
            }
          }
        },
        error: function (error) {
          alert("An error occured while liking the post. Try again later.");
        },
      });
    });
  });
});

//AJAX for showing comment form 

let showComment = document.querySelectorAll(".comment-button");

Array.from(showComment).forEach(function (button) {
  button.addEventListener("click", () => {
    let commentForm = button.closest(".post").querySelector(".comment-container");

    if (commentForm.style.display === "none") {
      commentForm.style.display = "block";
    }
    else {
      commentForm.style.display = "none";
    }
  })
});


// AJAX for adding comments
let commentForms = document.getElementsByClassName("comment-form");
Array.from(commentForms).forEach(function (form) {
  form.addEventListener("submit", function (event) {
    event.preventDefault();

    let userID = form.querySelector(".user-id").getAttribute("data-user-id");
    let postID = form.querySelector(".post-id").getAttribute("data-post-id");
    let commentText = form.querySelector(".comment-1").value.trim();
    let commentsSection = form.closest(".post").querySelector(".comments");
    let errorMessage = form.querySelector(".error-message");

    let requestData = {
      action: "addComment",
      user_id: userID,
      post_id: postID,
      content: commentText,
    };

    if (commentText == "") {
      errorMessage.style.display = "block";
      return;
    }

    errorMessage.style.display = "none";

    $.ajax({
      url: "/chatlink/php/api.php",
      type: "POST",
      data: JSON.stringify(requestData),
      contentType: "application/json",

      success: function (response) {
        let res = JSON.parse(response);

        if (res.status === "success") {
          // Append the new comment to the comments section

          let commentContainer = form.closest(".post").querySelector(".buttons");
          let commentSpan = commentContainer.querySelector(".comment-buttons > span");

          let currentCount = parseInt(commentSpan.innerHTML, 10);
          if (!isNaN(currentCount) && currentCount > 0) {
            commentSpan.textContent = currentCount + 1;
          }

          // Clear the comment input
          form.querySelector(".comment-1").value = "";

          res.status = "";
        }
      },
      error: function (error) {
        alert("An error occurred while adding the comment. Try again later.");
      },
    });
  });
});

// AJAX for showing comments for each post - Load comments PART
let postComments = document.getElementsByClassName("load-comments-button");
Array.from(postComments).forEach(function (button) {
  let loadCount = 0;
  let length = 0;
  let userID = parseInt(localStorage.getItem("id"));

  button.addEventListener("click", () => {
    let postID = button.getAttribute("data-post-id");
    let commentsList = button.closest(".post").querySelector(".comments");
    let viewLess = button.closest(".post").querySelector(".view-less-button");
    let commentsSection = button
      .closest(".post")
      .querySelector(".comments-container");
    let emptyCommentsSection = button
      .closest(".post")
      .querySelector(".empty-comments-bar");

    if (loadCount === 0) {
      let requestData = {
        action: "getComments",
        post_id: postID,
      };

      $.ajax({
        url: "/chatlink/php/api.php",
        type: "POST",
        data: JSON.stringify(requestData),
        contentType: "application/json",

        success: function (response) {
          let res = JSON.parse(response);
          length = res.length;

          if ((res.length > 0) & (loadCount === 0)) {
            // Append the new comment to the comments section
            for (let i = 0; i < res.length; i += 4) {
              let newComment = document.createElement("li");
              newComment.innerHTML = `<p class="comment-username">${res[i + 1]} <span>${res[i]}</span></p>`;
              newComment.classList.add("comment");

              if (userID === res[i + 2]) {
                newComment.innerHTML +=
                  `<button class='delete-comment-button' data-comment-id='${res[i + 3]}'>Delete</button>`;
                newComment.setAttribute("data-comment-id", res[i + 3]);
              }

              commentsList.appendChild(newComment);
            }

            let deleteButtons = document.querySelectorAll(".delete-comment-button");

            deleteButtons.forEach(function (button) {
              button.addEventListener("click", function () {
                // Retrieve the comment ID from the 'data-comment-id' attribute
                let commentId = button.getAttribute("data-comment-id");

                // Now you can use the commentId as needed, for example, delete the comment with this ID
                deleteComment(button, commentId);
              });
            });

            commentsSection.style.display = "block";
          } else if (res.length === 0) {
            emptyCommentsSection.style.display = "block";
          }

          loadCount++;
          button.style.display = "none";
          viewLess.style.display = "block";
        },
        error: function (error) {
          alert(
            "An error occurred while loading the comments. Try again later."
          );
        },
      });
    } else if (length === 0) {
      emptyCommentsSection.style.display = "block";
      button.style.display = "none";
      viewLess.style.display = "block";
    } else {
      commentsSection.style.display = "block";
      button.style.display = "none";
      viewLess.style.display = "block";
    }
  });
});

// AJAX for showing comments for each post - View less PART
let viewLess = document.getElementsByClassName("view-less-button");

Array.from(viewLess).forEach(function (button) {
  button.addEventListener("click", () => {
    let commentsSection = button
      .closest(".post")
      .querySelector(".comments-container");
    let emptyCommentsSection = button
      .closest(".post")
      .querySelector(".empty-comments-bar");
    let loadComments = button
      .closest(".post")
      .querySelector(".load-comments-button");

    if (emptyCommentsSection.style.display === "block") {
      emptyCommentsSection.style.display = "none";
      button.style.display = "none";
      loadComments.style.display = "block";
      loadComments.setAttribute("data-comments-loaded", "false");
    } else if (commentsSection.style.display === "block") {
      commentsSection.style.display = "none";
      loadComments.style.display = "block";
      button.style.display = "none";
      loadComments.setAttribute("data-comments-loaded", "false");
    }
  });
});

// Function to delete the comment (you should implement this according to your needs)
function deleteComment(button, commentID) {
  let requestData = {
    action: "deleteComment",
    comment_id: commentID
  };

  $.ajax({
    url: "/chatlink/php/api.php",
    type: "POST",
    data: JSON.stringify(requestData),
    contentType: "application/json",
    success: function (response) {
      if (Boolean(response) === true) {

        let commentContainer = button.closest(".post").querySelector(".buttons");
        let commentSpan = commentContainer.querySelector(".comment-buttons > span");

        let elementToRemove = button.closest(".comments").querySelector('.comment[data-comment-id="' + commentID + '"]');

        if (elementToRemove) {
          elementToRemove.remove();

          let currentCount = parseInt(commentSpan.innerHTML, 10);
          if (!isNaN(currentCount) && currentCount > 0) {
            commentSpan.textContent = currentCount - 1;
          }
        } else {
          alert("Element not found.");
        }
      } else {
        alert("An error occured please refresh the page and try again.");
      }
    },

    error: function (error) {
      alert("An error occured please refresh the page and try again.");
    },
  });
}

let followButton = document.getElementsByClassName("follow-button");
Array.from(followButton).forEach(function (button) {
  button.addEventListener("click", () => {
    let follower_id = parseInt(localStorage.getItem("id"));
    let following_id = button.getAttribute("data-user-id");
    let username = button.closest(".post").querySelector(".poster-username");
    let requestData = {
      action: "followButton",
      follower_id: follower_id,
      following_id: following_id,
    };

    $.ajax({
      url: "/chatlink/php/api.php",
      type: "POST",
      data: JSON.stringify(requestData),
      contentType: "application/json",

      success: function (response) {
        let res = JSON.parse(response);

        if (res.status === "following") {
          button.innerHTML = "Following";
        } else if (res.status === "not following") {
          button.innerHTML = "Follow";
        }
      },

      error: function (error) {
        alert("Something went wrong");
      },
    });
  });
});

let blockUser = document.getElementsByClassName("block-user-button");
Array.from(blockUser).forEach(function (button) {
  button.addEventListener("click", () => {
    let user_id = parseInt(localStorage.getItem("id"));
    let block_user_id = button.getAttribute("data-user-id");
    let username = button.closest(".post").querySelector(".poster-username");
    let requestData = {
      action: "blockUser",
      user_id: user_id,
      block_user_id: block_user_id,
    };

    $.ajax({
      url: "/chatlink/php/api.php",
      type: "POST",
      data: JSON.stringify(requestData),
      contentType: "application/json",

      success: function (response) {
        let res = JSON.parse(response);

        if (res.status === "blocked") {
          button.innerHTML = "Blocked";
          alert("The user were blocked successfully. Now you will not see his posts.");
          button.closest(".post").remove();
        } else if (res.status === "not blocked") {
          button.innerHTML = "Block User";
        }
      },

      error: function (error) {
        alert("Something went wrong");
      },
    });
  });
});

let more = document.querySelector("#alt-menu");
let btn = more.querySelector("#more-button");
let menu = more.querySelector("#menu");

btn.addEventListener("click", () => {

  if (menu.style.display === "none") {
    menu.style.display = "block";
  }
  else {
    menu.style.display = "none";
  }
});



let moreMenuButton = document.querySelectorAll(".more-menu-button");
Array.from(moreMenuButton).forEach(function (button) {
  button.addEventListener("click", () => {
    let menu = button.closest(".post").querySelector(".more-menu-attributes");

    if (menu.style.display === "none") {
      menu.style.display = "block";
    }
    else {
      menu.style.display = "none";
    }
  })
});


function loadPage(url) {
  document.getElementById('settingFrame').src = url;
}


let deletePost = document.querySelectorAll(".delete-post");

Array.from(deletePost).forEach(function (button) {
  button.addEventListener("click", () => {
    let confirmation = confirm("Are you sure you want to delete the post");
    let post_id = button.getAttribute("data-post-id");


    let requestData = {
      action: "deletePost",
      post_id: post_id
    }

    if (confirmation) {
      $.ajax({
        url: "/chatlink/php/api.php",
        type: "POST",
        data: JSON.stringify(requestData),
        contentType: "application/json",

        success: function (response) {
          let res = JSON.parse(response);

          if (res.status === true) {
            alert(res.message);
            button.closest(".post").remove();
          } else {
            alert(res.message);
          }
        },

        error: function (error) {
          alert("Something went wrong");
        },
      })
    }
  })
});
