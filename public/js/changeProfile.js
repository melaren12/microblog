import {Fetcher} from "../../src/classes/js/Fetcher.js";
import {AvatarZoom} from "../../src/classes/js/AvatarZoom.js";
import {AvatarPreview} from "../../src/classes/js/AvatarPreview.js";
import {PhotosManager} from "../../src/classes/js/PhotosManager.js";
import {PostsManager} from "../../src/classes/js/PostsManager.js";
import {UserManager} from "../../src/classes/js/UserManager.js";

const fetcher = new Fetcher('/controllers/');

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
    const profileInfo = document.getElementById('profile-info');
    profileInfo.innerHTML = `
        <div class="avatar">
            <img src="/public/uploads/avatars/${encodeURIComponent(user.avatar)}" alt="Avatar" loading="lazy">
        </div>
        <h3>${encodeURIComponent(user.name)}</h3>
    `;
}

function renderPosts(posts) {
    const postsContainer = document.getElementById('posts-container');
    postsContainer.innerHTML = posts.map(post => `
        <article class="post" data-id="${post.id}">
            <p class="intro">${decodeURIComponent(post.content).replace(/\n/g, '<br>')}</p>
            <footer class="time">${post.created_at}</footer> 
            <button class="delete-post post-delete-btn" data-id="${post.id}"><img src="/public/icons/delete.png"></button> 
        </article>
    `).join('');
}

function renderPhotos(photos) {
    const photosContainer = document.getElementById('photos-container');

    if (!photosContainer) {
        console.error('Photos container not found');
        return;
    }
    if (!Array.isArray(photos)) {
        console.error('photos is not an array:', photos);
        photosContainer.innerHTML = '<p class="error">Error: Photos not uploaded.</p>';
        return;
    }
    if (photos.length === 0) {
        photosContainer.innerHTML = '<p class="info">There are no photos.</p>';
        return;
    }
    photosContainer.innerHTML = photos.map(photo => `
        <div class="photo" data-id="${photo.id}">
            <img src="${encodeURIComponent(photo.photo_path)}" alt="${encodeURIComponent(photo.caption || 'Photo')}" width="200">
            <p>${encodeURIComponent(photo.caption || '')}</p>
            <button class="delete-photo photo-delete-btn btn" data-id="${photo.id}">
               Delete
            </button>
        </div>
    `).join('');
}