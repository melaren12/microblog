import {Fetcher} from "../../src/classes/js/Fetcher.js";
import {PhotosManager} from "../../src/classes/js/PhotosManager.js";
import {PostsManager} from "../../src/classes/js/PostsManager.js";
import {UserManager} from "../../src/classes/js/UserManager.js";
import {RenderTemplate} from "../../src/classes/js/RenderTemplate.js";

const fetcher = new Fetcher('/controllers/');
const render = new RenderTemplate();

function getQueryParam(name) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(name);
}

const userId = getQueryParam('user_id');

if (userId) {
    const decodedUserId = decodeURIComponent(userId);
    console.log('Decoded User ID:', decodedUserId);
} else {
    console.log('User ID not found in URL');
}
document.addEventListener('DOMContentLoaded', async function () {
    const photos = new PhotosManager(fetcher, 'photo');
    const posts = new PostsManager(fetcher, 'post');
    const user = new UserManager(fetcher);


    try {
        const userData = await user.init(userId);
        if (userData) {
            renderUserProfile(userData);
        } else {
            console.error("User Data not found");
        }

        const postsData = await posts.initPostsData();
        if (postsData && userData.id && Array.isArray(postsData.posts)) {
            const userPosts = postsData.posts.filter(post => post.user_id == userId);
            renderPosts(userPosts);
        }
        const photosData = await photos.initPhotos(userId);
        if (photosData && Array.isArray(photosData.photos)) {
            renderPhotos(photosData.photos);
        }
    } catch (error) {
        alert(error);
    }
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
    const renderTemplate = document.getElementById('post_template')?.innerHTML;
    const container = document.getElementById('posts-container');

    if (!container) {
        console.error('Posts container not found');
        return;
    }
    if (!Array.isArray(posts)) {
        console.error('photos is not an array:', photos);
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
                created_at: post.created_at
            }
            const postEl = render.renderTemplate(renderTemplate, data);

            container.appendChild(postEl);
        })
    }
}

function renderPhotos(photos) {

    const renderTemplate = document.getElementById('photo_template')?.innerHTML;
    const container = document.getElementById('photos-container');

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