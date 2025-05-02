<main class="profile-container">
    <div class="left-cont">
        <h1>Profile</h1>
        <div class="profile-info" id="profile-info"></div>
        <a href="/controllers/profile-page">
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
    <div class="photo" data-id="${id}">
        <img src="${src}" width="200" alt="Photo">
    </div>
</template>

<template id="post_template">
    <article class="post" data-id="${id}">
        <p class="intro">${content}</p>
        <footer class="time">${created_at}</footer>
    </article>
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