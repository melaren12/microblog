import {UserManager} from "../../src/classes/js/UserManager.js";
import {Fetcher} from "../../src/classes/js/Fetcher.js";
import {PostsManager} from "../../src/classes/js/PostsManager.js";

const fetcher = new Fetcher('/controllers/');

document.addEventListener('DOMContentLoaded', async function () {
    const user = new UserManager(fetcher);
    const postsManager = new PostsManager(fetcher);

    try {
        const userData = await user.init();
        const userId = userData.id;
        if (userData) {
            renderUserProfile(userData);

        } else {
            console.error("User Data not found");
        }
        const postsData = await postsManager.initPostsData();

        if (postsData && userData.id && Array.isArray(postsData.posts)) {
            renderPosts(postsData.posts, userData.id)
        }
        postsManager.initPostForm(userId);
    } catch (error) {
        alert(error)
    }
});

function renderUserProfile(user) {
    const profileInfo = document.getElementById('profile-info');
    profileInfo.innerHTML = `
        <div class="avatar">
             <img src="/public/uploads/avatars/${encodeURIComponent(user.avatar)}" alt="Avatar" loading="lazy">
        </div>
        <h3>${encodeURIComponent(user.name)}</h3>
            <a href="/controllers/ChangeProfile.php" class="btn">Edit</a>
    `;
}

function renderPosts(posts, currentUserId) {
    const postsContainer = document.getElementById('posts-container');
    postsContainer.innerHTML = posts.map(post => `
        <article class="post" data-id="${post.id}">
            <div class="post-text">
                <p class="user-name">
                    <strong>
                        <a href="${currentUserId === post.user_id ? '/controllers/ChangeProfile.php' : `/controllers/GuestPage.php?user_id=${encodeURIComponent(post.user_id)}`}">
                           ${encodeURIComponent(post.user_name)} ${encodeURIComponent(post.user_lastname)}
                        </a>
                    </strong>
                </p>
                <p class="intro">${decodeURIComponent(post.content).replace(/\n/g, '<br>')}</p>
                <time class="time">${post.created_at}</time>
            </div>
        </article>
    `).join('');
}

