import {Fetcher} from "../../src/classes/js/Fetcher.js";
import {AvatarZoom} from "../../src/classes/js/AvatarZoom.js";
import {AvatarPreview} from "../../src/classes/js/AvatarPreview.js";
import {PhotosManager} from "../../src/classes/js/PhotosManager.js";
import {PostsManager} from "../../src/classes/js/PostsManager.js";
import {UserManager} from "../../src/classes/js/UserManager.js";
import {RenderTemplate} from "../../src/classes/js/RenderTemplate.js";

const fetcher = new Fetcher('/controllers/');
const render = new RenderTemplate();

document.addEventListener('DOMContentLoaded', async function () {
    const photos = new PhotosManager(fetcher, 'photo');
    const posts = new PostsManager(fetcher, 'post');
    const user = new UserManager(fetcher);

    try {
        const userData = await user.init();
        if (userData) {
            renderUserProfile(userData);
        } else {
            console.error("User Data not found");
        }
        const postsData = await posts.initPostsData();

        if (postsData && userData.id && Array.isArray(postsData.posts)) {
            const userPosts = postsData.posts.filter(post => post.user_id === userData.id);
            if (userPosts.length > 0) {
                renderPosts(userPosts, userData.id);
            }
        }

        const photosData = await photos.initPhotos(userData.id);

        if (photosData) {
            renderPhotos(photosData.photos);
        }

    } catch (error) {
        alert(error)
    }

    new AvatarZoom('.avatar img', '.large-avatar-zoom', '#zoomed-avatar');
    new AvatarPreview('avatar', '.avatar-preview', '#avatar-preview-img', '.close-preview');

    posts.initDeletePosts('.delete-post', '.post');
    photos.initDeletePhotos('toArchive.php', '.delete-photo', '.photo',);
});

function renderUserProfile(user) {
    const container = document.getElementById('profile-info');
    const renderTemplate = document.getElementById('user_template')?.innerHTML;

    if (!(container instanceof HTMLElement)) {
        console.error('Profile container not found');
        return;
    }

    if (renderTemplate && container) {

        container.innerHTML = '';

        const data = {
            userName: decodeURIComponent(user.name ),
            src: `/public/uploads/avatars/${encodeURIComponent(user.avatar)}`,
        }
        const userEl = render.renderTemplate(renderTemplate, data);

        container.appendChild(userEl);

    }
}

function renderPosts(posts) {
    const container = document.getElementById('posts-container');
    const renderTemplate = document.getElementById('post_template')?.innerHTML;

    if (!(container instanceof HTMLElement)) {
        console.error('Photos container not found');
        return;
    }

    if (!Array.isArray(posts)) {
        console.error('photos is not an array:', posts);
        container.innerHTML = '<p class="error">Error: Photos not uploaded.</p>';
        return;
    }

    if (posts.length === 0) {
        container.innerHTML = '<p class="info">There are no photos.</p>';
        return;
    }

    if (renderTemplate && container) {
        posts.forEach(post => {
            const data = {
                id:post.id,
                content: post.content,
                src:'/public/icons/delete.png',
                created_at: post.created_at
            }
            const postEl = render.renderTemplate(renderTemplate, data);

            container.appendChild(postEl);
        })
    }
}

function renderPhotos(photos) {
    const container = document.getElementById('photos-container');
    const renderTemplate = document.getElementById('photo_template')?.innerHTML;

    if (!container) {
        console.error('Photos container not found');
        return;
    }
    if (!Array.isArray(photos)) {
        console.error('photos is not an array:', photos);
        container.innerHTML = '<p class="error">Error: Photos not uploaded.</p>';
        return;
    }
    if (photos.length === 0) {
        container.innerHTML = '<p class="info">There are no photos.</p>';
        return;
    }

    if (renderTemplate && container) {
        photos.forEach(photo => {
            const data = {
                id:photo.id,
                src: encodeURIComponent(photo.photo_path)
            }
            const photoEl = render.renderTemplate(renderTemplate, data);

            container.appendChild(photoEl);
        })
    }
}
