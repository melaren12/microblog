import {PhotosManager} from "../../src/classes/js/PhotosManager.js";
import {Fetcher} from "../../src/classes/js/Fetcher.js";
import {UserManager} from "../../src/classes/js/UserManager.js";
import {RenderTemplate} from "../../src/classes/js/RenderTemplate.js";

const fetcher = new Fetcher('/controllers/');
const render = new RenderTemplate();

document.addEventListener('DOMContentLoaded', async function () {
    const photos = new PhotosManager(fetcher, 'photo');
    const user = new UserManager(fetcher);

    photos.initDeletePhotos('deleteAction.php', '.delete-photo', '.photo');
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

function loadArchivedPhotos(photosData) {

    const photos = new PhotosManager(fetcher, 'photo');
    const container = document.getElementById('photos-container');
    const renderTemplate = document.getElementById('photo_template')?.innerHTML;

    if (!container) {
        console.error('Photos container not found');
        return;
    }
    if (!Array.isArray(photosData.photos)) {
        console.error('photos is not an array:', photos);
        container.innerHTML = '<p class="error">Error: Photos not uploaded.</p>';
        return;
    }
    if (photos.length === 0) {
        container.innerHTML = '<p class="info">There are no photos.</p>';
        return;
    }

    if (renderTemplate && container) {
        photosData.photos.forEach(photo => {
            const data = {
                id:photo.id,
                src: photo.photo_path
            }
            const photoEl = render.renderTemplate(renderTemplate, data);

            container.appendChild(photoEl);

            photos.initDeletePhotos('deleteAction.php', '.delete-photo', '.photo');
            photos.initRestorePhotos('restorePhotos.php', '.restore-photo', '.photo');
        })
    }
}
