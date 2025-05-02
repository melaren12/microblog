<main class="profile-container">
    <aside class="sidebar">
        <h1>Microblog</h1>
        <section class="profile-info" id="profile-info">

        </section>

        <form action="/controllers/postSection.php" method="post" class="post-form" id="post-form">
            <label for="content">What's new with you?</label>
            <textarea id="content" name="content" placeholder="What's new with you?" required></textarea>
            <button type="submit" class="btn">Publish</button>
        </form>
        <a href="/controllers/logout.php" class="btn logout">Logout</a>
    </aside>
    <section class="content" id="posts-container">

    </section>
</main>

<template id="post_template">
    <article class="post" data-id="${id}">
        <div class="post-text">
            <p class="user-name">
                <strong><a class="link" href="${link}">${userName}</a></strong>
            </p>
            <p class="intro">
                ${content}
            </p>
        </div>
        <div class="time">
            ${createdAt}
        </div>
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
        <a href="${link}" class="btn">Edit</a>
    </div>
</template>

