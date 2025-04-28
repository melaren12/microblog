export class PhotosManager {
    constructor(fetcher, type) {
        this.fetcher = fetcher;
        this.type = type;
    }

    async initPhotos(userId, archived = false) {
        try {
            const photosData = await this.fetcher.post('../../api/api_photos.php', {
                user_id: userId,
                archived: archived
            });
            if (photosData.success) {
                return photosData;
            } else {
                return false;
            }
        } catch (error) {
            console.error('Initialization error:', error);
        }
    }

    initDeletePhotos(fileName, selector, itemClass) {
        const buttons = document.querySelectorAll(selector);
        buttons.forEach(button => {
            button.addEventListener('click', async (e) => {
                e.preventDefault();
                const id = button.getAttribute('data-id');
                const element = button.closest(itemClass);
                await this.deletePhoto(fileName, id, this.type, element);
            });
        });
    }

    async deletePhoto(fileName, id, type, element) {
        try {
            const data = await this.fetcher.post(fileName, {id, type});
            if (data.success) {
                element.remove();
            } else {
                alert(`Error deleting ${type}: ${data.error || 'Unknown error'}`);
            }
        } catch (error) {
            alert(`Error deleting ${type}. Please try again. ${error}`);
        }
    }

    initRestorePhotos(fileName, selector, itemClass) {
        const buttons = document.querySelectorAll(selector);
        buttons.forEach(button => {
            button.addEventListener('click', async (e) => {
                e.preventDefault();
                const id = button.getAttribute('data-id');
                const element = button.closest(itemClass);
                await this.restorePhoto(fileName, id, this.type, element);
            });
        });
    }

    async restorePhoto(fileName, id, type, element) {
        try {
            const data = await this.fetcher.post(fileName, {id, type});
            if (data.success) {
                element.remove();
            } else {
                alert(`Error deleting ${type}: ${data.error || 'Unknown error'}`);
            }
        } catch (error) {
            alert(`Error deleting ${type}. Please try again.`);
        }
    }
}