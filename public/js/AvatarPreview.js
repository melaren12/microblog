export class AvatarPreview {
    constructor(inputSelector, previewSelector, previewImgSelector, closeButtonSelector) {
        this.input = document.getElementById(inputSelector);
        this.preview = document.querySelector(previewSelector);
        this.previewImg = document.querySelector(previewImgSelector);
        this.closeButton = document.querySelector(closeButtonSelector);

        this.init();
    }

    init() {
        if (!this.input || !this.preview || !this.previewImg || !this.closeButton) {
            console.error('AvatarPreview: One or more elements not found.');
            return;
        }

        this.input.addEventListener('change', (event) => this.handleFileChange(event));
        this.closeButton.addEventListener('click', () => this.closePreview());
    }

    handleFileChange(event) {
        const file = event.target.files[0];
        if (file) {
            if (!file.type.startsWith('image/')) {
                alert('Please select an image file (JPG, PNG, etc.)');
                this.input.value = '';
                this.preview.classList.remove('active');
                return;
            }

            const reader = new FileReader();
            reader.onload = (e) => {
                this.previewImg.src = e.target.result;
                this.preview.classList.add('active');
            };
            reader.onerror = () => {
                alert('Error reading the file. Please try again.');
                this.preview.classList.remove('active');
            };
            reader.readAsDataURL(file);
        } else {
            this.preview.classList.remove('active');
        }
    }

    closePreview() {
        this.preview.classList.remove('active');
        this.input.value = '';
    }
}