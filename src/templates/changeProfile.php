<main class="profile-container">
    <div class="large-avatar-zoom">
        <img id="zoomed-avatar" src="" alt="Zoomed Avatar">
    </div>
    <div class="left-cont">
        <h2>Profile</h2>
        <div class="profile-info" id="profile-info">

        </div>

        <form method="post" enctype="multipart/form-data" id="photo-form">
            <div class="avatar-container">
                <div class="custom-file-upload">
                    <label for="avatar" class="input-label btn">Change avatar</label>
                    <input type="file" name="avatar" id="avatar" accept="image/*">
                </div>
                <br>
                <div class="avatar-preview">
                    <img class="avatar-preview-img" id="avatar-preview-img" src="#" alt="Avatar Preview"">
                    <button type="submit" class="btn avatar-btn">Upload</button>
                    <button type="button" class="close-preview"
                    "><img src="/public/icons/close.png" alt="#">
                </div>
            </div>
        </form>

        <a href="/controllers/profilePage.php">
            <button class="btn logout">Profile</button>
        </a>
    </div>
    <div class="right-cont">
        <div class="left-side" id="posts-container">
            <h2>Posts</h2>
        </div>
        <div class="right-side">
            <div class="action-cont">
                <form method="post" enctype="multipart/form-data">
                    <div class="upload">
                        <div>
                            <label for="photo_path" class="input-label btn">Add Photo</label>
                            <input type="file" name="photo_path" id="photo_path" accept="image/*">
                        </div>
                        <button type="submit" class="btn">Upload</button>
                    </div>
                </form>
                <a href="/controllers/archivePage.php">
                    <button class="btn archive">Archive</button>
                </a>
            </div>

            <div class="photos" id="photos-container">

            </div>
        </div>
    </div>
</main>

<template id="post_template">
    <article class="post" data-id="${id}">
        <p class="intro">${content}</p>
        <footer class="time">${created_at}</footer>
        <button class="delete-post post-delete-btn" data-id="${id}"><img src="${src}"></button>
    </article>
</template>

<template id="photo_template">
    <div class="photo" data-id="${id}">
        <img src="${src}" alt="Photo">
        <button class="delete-photo photo-delete-btn btn" data-id="${id}">Delete</button>
    </div>
</template>

<template id="user_template">
    <div class="profile">
        <div class="avatar">
            <img src="${src}" alt="${alt}" loading="lazy">
        </div>
        <div class="user-name">
            <h3>${userName}</h3>
        </div>
    </div>
</template>



