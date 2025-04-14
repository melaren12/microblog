export class AvatarZoom {
    constructor(avatarSelector, modalSelector, zoomedAvatarSelector) {
        this.avatar = document.querySelector(avatarSelector);
        this.modal = document.querySelector(modalSelector);
        this.zoomedAvatar = document.querySelector(zoomedAvatarSelector);
        this.init();
    }

    init() {
        if (!this.avatar || !this.modal || !this.zoomedAvatar) {
            console.error('AvatarZoom: One or more elements not found.', {
                avatar: this.avatar,
                modal: this.modal,
                zoomedAvatar: this.zoomedAvatar,
            });
            return;
        }

        this.avatar.addEventListener('click', () => this.showModal());
        this.modal.addEventListener('click', () => this.hideModal());
    }

    showModal() {
        const avatarSrc = this.avatar.getAttribute('src');
        if (!avatarSrc) {
            console.error('AvatarZoom: Avatar image src is missing.');
            return;
        }
        this.zoomedAvatar.setAttribute('src', avatarSrc);
        this.modal.classList.add('active');
    }

    hideModal() {
        this.modal.classList.remove('active');
    }
}