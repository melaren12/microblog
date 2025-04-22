import {Fetcher} from "../../src/classes/js/Fetcher.js";
import {PhotosManager} from "../../src/classes/js/PhotosManager.js";
import {PostsManager} from "../../src/classes/js/PostsManager.js";
import {UserManager} from "../../src/classes/js/UserManager.js";

const fetcher = new Fetcher('/controllers/');


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

    // const renderTemplate = document.getElementById('photo_template');
    // photos.forEach(photo => {
    //     const data = {
    //         path: encodeURIComponent(photo.photo_path),
    //         alt: encodeURIComponent(photo.caption || 'Photo'),
    //         name: encodeURIComponent(photo.caption || ''),
    //     }
    //     const photoEl = _render(renderTemplate, data);
    //     photosContainer.appendChild(photoEl)
    // })

    photosContainer.innerHTML = photos.map(photo => `
        <div class="photo" data-id="${photo.id}">
            <img src="${encodeURIComponent(photo.photo_path)}" alt="${encodeURIComponent(photo.caption || 'Photo')}" width="200">
            <p>${encodeURIComponent(photo.caption || '')}</p>
        </div>
    `).join('');
}

//
// function _render(renderTemplate, data) {
//     return document.createElement('div');
// }


