import {UserManager} from "../../src/classes/js/UserManager.js";
import {Fetcher} from "../../src/classes/js/Fetcher.js";
import {PostsManager} from "../../src/classes/js/PostsManager.js";
import {RenderTemplate} from "../../src/classes/js/RenderTemplate.js";
import {PhotosManager} from "../../src/classes/js/PhotosManager.js";

const fetcher = new Fetcher('/controllers/');
const render = new RenderTemplate();

document.addEventListener('DOMContentLoaded', async function () {
    const user = new UserManager(fetcher);
    const postsManager = new PostsManager(fetcher);
    const photosManager = new PhotosManager(fetcher);

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

        initPostForm(userId);
    } catch (error) {
        alert(error)
    }
});

function initPostForm(userId) {
    const form = document.getElementById('post-form');
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const content = form.querySelector('#content').value;
        try {
            const data = await fetcher.post('postSection.php', {
                content,
                user_id: userId
            });
            if (data.success) {
                form.reset();
                const postsData = await fetcher.post('../../api/api_posts.php', {});
                const userData = await fetcher.post('../../api/api_user.php', {});
                if (postsData.success && userData.success) {

                    renderPosts(postsData.posts, userData.user.id);
                }
            } else {
                alert(`Error while publishing: ${data.error || 'Unknown error'}`);
            }
        } catch (error) {
            alert('Error sending post. Try again.');
            console.error(error);
        }
    });
}

function renderUserProfile(user) {
    const containerId = 'profile-info';
    const container = document.getElementById(containerId);
    const renderTemplate = document.getElementById('user_template')?.innerHTML;

    if (!(container instanceof HTMLElement)) {
        console.error(`${container} container not found`);
        return;
    }

    if (renderTemplate && container) {

        container.innerHTML = '';

        const data = {
            userName: decodeURIComponent(user.name + ' ' + user.lastName),
            src: `/public/uploads/avatars/${encodeURIComponent(user.avatar)}`,
            link: '/controllers/change-profile'
        }
        const userEl = render.renderTemplate(renderTemplate, data);

        container.appendChild(userEl);
    }
}

export function renderPosts(posts, currentUserId) {

    const containerId = 'posts-container';
    const container = document.getElementById(containerId);
    const renderTemplate = document.getElementById('post_template')?.innerHTML;

    if (!(container instanceof HTMLElement)) {
        console.error(`${container} container not found`);
        return;
    }

    if (!Array.isArray(posts)) {
        console.error('posts is not an array:', posts);
        container.innerHTML = '<p class="error">Error: Posts not uploaded</p>';
        return;
    }

    if (posts.length === 0) {
        container.innerHTML = '<p class="info">There are no posts</p>';
        return;
    }

    if (renderTemplate && container) {

        container.innerHTML = '';
        posts.forEach(post => {
            const data = {
                id: post.id,
                userName: decodeURIComponent(post.user_name + ' ' + post.user_lastname),
                content: post.content,
                createdAt: post.created_at,
                link: post.user_id === currentUserId ? `/controllers/change-profile?user_id=${encodeURIComponent(post.user_id)}` : `/controllers/guest-page?user_id=${encodeURIComponent(post.user_id)}`
            }
            const postEl = render.renderTemplate(renderTemplate, data);

            container.appendChild(postEl);
        })
    }
}