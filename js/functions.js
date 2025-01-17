// Switch function

const switchTheme = () => {
  // Get root element and data-theme value
  const rootElem = document.documentElement;
  let dataTheme = rootElem.getAttribute("data-theme");

  let newTheme = "";

  if (dataTheme === "light") {
    newTheme = "dark";
  }
  else {
    newTheme = "light";
  }

  rootElem.setAttribute("data-theme", newTheme);

  localStorage.setItem("theme", newTheme);

  window.parent.location.reload(true);
}

// Add event listener for the theme switcher
if (document.querySelector("#theme-switcher")) {
  document.querySelector("#theme-switcher").addEventListener("click", switchTheme);
}

function likeCheck() {

  let likeButton = document.querySelectorAll(".like-button");
  Array.from(likeButton).forEach(function (button) {
    let userID = button.getAttribute("data-user-id");
    let postID = button.getAttribute("data-post-id");

    let requestData = {
      action: "checkLike",
      user_id: userID,
      post_id: postID
    };

    $.ajax({
      url: "/chatlink/php/api.php",
      type: "POST",
      data: JSON.stringify(requestData),
      contentType: "application/json",

      success: function (response) {
        let res = JSON.parse(response);

        if (res.status === "liked") {
          button.innerHTML = "<i class='fa-solid fa-heart'></i>";
        }
        else {
          button.innerHTML = "<i class='fa-regular fa-heart'></i>";
        }
      },
      error: function (error) {
        alert("An error occured while liking the post. Try again later.");
      },
    });
  })
}



function followCheck() {

  let followCheck = document.querySelectorAll(".follow-button");
  Array.from(followCheck).forEach(function (button) {
    let userID = localStorage.getItem("id");
    let following_id = button.getAttribute("data-user-id");

    let requestData = {
      action: "checkFollow",
      user_id: userID,
      following_id: following_id
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
        }
        else {
          button.innerHTML = "Follow";
        }
      },
      error: function (error) {
        alert("An error occured while liking the post. Try again later.");
      },
    });
  })
}


function blockCheck() {
  let blockCheck = document.querySelector(".block-user");
  let userID = localStorage.getItem("id");
  let blocked_id = blockCheck.getAttribute("data-user-id");

  let requestData = {
    action: "checkBlock",
    user_id: userID,
    blocked_id: blocked_id
  };

  $.ajax({
    url: "/chatlink/php/api.php",
    type: "POST",
    data: JSON.stringify(requestData),
    contentType: "application/json",

    success: function (response) {
      let res = JSON.parse(response);

      if (res.status === "blocked") {
        blockCheck.disabled = true;
        blockCheck.style.backgroundColor = " rgba(192, 192, 192, 0.5)";
        blockCheck.innerHTML = "Blocked";
      }
      else {
        blockCheck.innerHTML = "Block";
      }
    },
    error: function (error) {
      alert("An error occured while liking the post. Try again later.");
    },
  });
}


function downloadImage(url) {
  const link = document.createElement('a');
  link.href = url;
  link.download = 'image';
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
}

function loadProfilePost(postId) {
  // Make an AJAX request to a PHP script to generate the post HTML
  $.ajax({
    url: '/chatlink/php/modalPost.php', // Replace 'generate_profile_post.php' with the actual PHP script path
    method: 'POST',
    data: { postId: postId }, // Send the post ID to the PHP script
    success: function (response) {
      // Insert the generated post HTML into the modal

      const res = JSON.parse(response);

      let mediaType = `<img src="${res.image_url}" alt="Post media" id="postMedia">`;

      let media = res.image_url.slice(-3);

      if (media === "mov" || media === "avi" || media === "mp4") {
        mediaType = `
        <video controls>
          <source src="${res.image_url}" type="video/${media}" class="postImg">
        </video>`;
      }

      const template = (res) => `
      <div class="modal" id="postModal">
        <div class="modal-content">
          <div class="user-info">
            <div style='display: flex;'>
              <img src="${res.profile}" alt="User profile picture" id="profilePicture" class="profile-picture">
              <span class="username" id="username">${res.username}</span>
            </div>
            <span class="close-button">&times;</span>
          </div>
          <div class="modal-body">
            <div class="post-media">
                ${mediaType}
            </div>
          </div>
      </div>`

      document.getElementById("modalContainer").style.display = "block";
      document.getElementById("modalContainer").innerHTML = template(res);

      const modal = document.getElementById("postModal");
      const closeButton = document.querySelector(".close-button");

      closeButton.addEventListener("click", () => {
        modal.style.display = "none";
        modal.remove();
      });

      // Show the modal for demo purposes
      modal.style.display = "block";
    },
    error: function (xhr, status, error) {
      console.error(error);
      // Handle error
    }
  });
}

