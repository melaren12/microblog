export class UserManager {
    constructor(fetcher) {
        this.fetcher = fetcher;
    }

    async init(userId) {
        try {
            const userData = await this.fetcher.post(
                '../../api/api_user.php',
                userId ? {user_id: userId} : {});
            if (userData.success && userData.user) {
                return userData.user;
            } else {
                this.renderError('Error: User not found.');
                window.location.href = 'login-page?error=user_not_found';
            }

        } catch (error) {
            console.error('Initialization error:', error);
            this.renderError('Error loading profile. Try again.');
        }
    }

    renderError(message) {
        const profileInfo = document.getElementById('profile-info');
        profileInfo.innerHTML = `<p>${message}</p>`;
    }
}