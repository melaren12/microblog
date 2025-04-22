<main class="profile-container">
    <div class="left-cont">
        <h1>Profile</h1>
        <div class="profile-info" id="profile-info"></div>
        <a href="/controllers/profile.php">
            <button class="btn">Back</button>
        </a>
    </div>
    <div class="right-cont">
        <div class="left-side">
            <h2>Posts</h2>
            <div id="posts-container"></div>
        </div>
        <div class="right-side">
            <h2>Photos</h2>
            <div id="photos-container" class="photos"></div>
        </div>
    </div>
</main>

<template id="photo_template">
    <div class="photo" data-id="${photo.id}">
        <img src="${encodeURIComponent(photo.photo_path)}" alt="${encodeURIComponent(photo.caption || 'Photo')}" width="200">
        <p>${encodeURIComponent(photo.caption || '')}</p>
    </div>
</template>