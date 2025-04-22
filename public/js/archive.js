import {PhotosManager} from "../../src/classes/js/PhotosManager.js";
import {Fetcher} from "../../src/classes/js/Fetcher.js";
import {UserManager} from "../../src/classes/js/UserManager.js";

const fetcher = new Fetcher('/controllers/');

document.addEventListener('DOMContentLoaded', async function () {
    const photos = new PhotosManager(fetcher, 'photo');
    const user = new UserManager(fetcher);

    photos.initDeletePhotos('delete.php', '.delete-photo', '.photo');
    photos.initRestorePhotos('restorePhotos.php', '.restore-photo', '.photo');

    try {
        const userData = await user.init();

        const userId = userData.id;

        const photosData = await photos.initPhotos(userId, true);
        if (photosData) {
            loadArchivedPhotos(photosData);
        }

    } catch (error) {
        alert(error)
    }
});

async function loadArchivedPhotos(photosData) {

    const photos = new PhotosManager(fetcher, 'photo');

    if (photosData && photosData.photos) {
        const container = document.getElementById('photos-container');
        container.innerHTML = '';
        photosData.photos.forEach(photo => {
            const photoElement = document.createElement('div');
            photoElement.className = 'photo';
            photoElement.innerHTML = `
                <img src="../${photo.photo_path}" alt="Photo">
                <div class="buttons">
                    <button class="delete-photo btn" data-id="${photo.id}">Delete</button>
                    <button class="restore-photo btn" data-id="${photo.id}">Restore</button>
                </div>
            `;
            container.appendChild(photoElement);
        });
        photos.initDeletePhotos('delete.php', '.delete-photo', '.photo');
        photos.initRestorePhotos('restorePhotos.php', '.restore-photo', '.photo');
    }
}