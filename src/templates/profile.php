<main class="profile-container">
    <aside class="sidebar">
        <h1>Microblog</h1>
        <section class="profile-info" id="profile-info">

        </section>
        <form action="/controllers/post.php" method="post" class="post-form" id="post-form">
            <label for="content">What's new with you?</label>
            <textarea id="content" name="content" placeholder="What's new with you?" required></textarea>
            <button type="submit" class="btn">Publish</button>
        </form>
        <a href="/controllers/logout.php" class="btn logout">Logout</a>
    </aside>
    <section class="content" id="posts-container">

    </section>
</main>
