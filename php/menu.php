<header id="header">
    <nav class="navigation-bar"> <!--  onclick="checkDropDown()" -->
        <div class="logo-box">
            <a href="/chatlink/index.php" class="link-logo">
                ChatLine
            </a>
        </div>
        <ul class="nav-links">
            <li>
                <a href="/chatlink/index.php">
                    <i class="fa-solid fa-house"></i>
                    <p>Home</p>
                </a>
            </li>
            <li>
                <a href="/chatlink/chats/users.php">
                    <i class="fa-solid fa-message"></i>
                    <p>Messages</p>
                </a>
            </li>
            <li>
                <a href="/chatlink/php/search-user.php">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <p>Search</p>
                </a>
            </li>
            <li>
                <a href="/chatlink/post/">
                    <i class="fa-regular fa-square-plus"></i>
                    <p>Create Post</p>
                </a>
            </li>

            <?php

            $user = getCurrentUser($current_user_id, "");
            $str = $user["profile_image"];

            $newStr = str_replace("..", "/chatlink", $str);
            $username = $user["username"];
            ?>

            <li>
                <a href="/chatlink/php/profile.php?username=<?php echo $username;  ?>">
                    <img src="<?php echo $newStr; ?>" alt='Profile Image' class="nav-small-img" loading='lazy' title='Profile'>
                    <p>Profile</p>
                </a>
            </li>
            <li id="alt-menu">
                <button type="button" id="more-button">
                    <i class="fa-solid fa-bars"></i>
                    <p>More</p>
                </button>


                <div id="menu" style="display: none;">
                    <ul>
                        <li>
                            <a href="/chatlink/php/settings.php">
                                <i class="fa-solid fa-gear"></i>
                                <p>Settings</p>
                            </a>
                        </li>
                        <li>
                            <button type="button" id="signOut">
                                <i class='fa-solid fa-right-from-bracket'></i>
                                <p>Sign Out</p>
                            </button>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
    </nav>
</header>