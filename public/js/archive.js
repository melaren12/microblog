import {PhotosManager} from "../../src/classes/js/PhotosManager.js";
import {Fetcher} from "../../src/classes/js/Fetcher.js";

const fetcher = new Fetcher('/controllers/');

document.addEventListener('DOMContentLoaded', function () {
    const photos = new PhotosManager(fetcher, 'photo');

    photos.initDeletePhotos('delete.php', '.delete-photo', '.photo' );
    photos.initRestorePhotos('restorePhotos.php', '.restore-photo', '.photo' );
});