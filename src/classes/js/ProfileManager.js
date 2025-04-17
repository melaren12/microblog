export class ProfileManager {
    constructor(fetcher) {
        this.fetcher = fetcher;
        this.currentUserId = null;
    }

    async init() {
        try {
            const userData = await this.fetcher.post('../../api/api_user.php', {});
            if (userData.success && userData.user) {
                this.currentUserId = userData.user.id;
                this.renderUserProfile(userData.user);
            } else {
                this.renderError('Error: User not found.');
                window.location.href = 'login.php?error=user_not_found';
            }

            const postsData = await this.fetcher.post('../../api/api_posts.php', {});
            if (postsData.success && Array.isArray(postsData.posts)) {
                this.renderPosts(postsData.posts, userData.user.id);
            } else {
                this.renderNoPosts();
            }


            setTimeout(() => this.initPostForm(), 0);
        } catch (error) {
            console.error('Initialization error:', error);
            this.renderError('Error loading profile. Try again.');
        }
    }

    renderUserProfile(user) {
        const profileInfo = document.getElementById('profile-info');
        profileInfo.innerHTML = `
            <div class="avatar">
                <img src="/public/uploads/avatars/${encodeURIComponent(user.avatar)}" alt="Аватар" loading="lazy">
            </div>
            <h3>${encodeURIComponent(user.name)}</h3>
            <a href="/controllers/ChangeProfile.php" class="btn">Edit</a>
        `;
    }

    renderPosts(posts, currentUserId) {
        const postsContainer = document.getElementById('posts-container');
        postsContainer.innerHTML = posts.map(post => `
            <article class="post" data-id="${post.id}">
                <div class="post-text">
                    <p class="user-name">
                        <strong>
                            <a href="${currentUserId === post.user_id ? '/controllers/ChangeProfile.php' : `/controllers/GuestPage.php?user_id=${encodeURIComponent(post.user_id)}`}">
                                ${encodeURIComponent(post.user_name)} ${encodeURIComponent(post.user_lastname)}
                            </a>
                        </strong>
                    </p>
                    <p class="intro">${decodeURIComponent(post.content).replace(/\n/g, '<br>')}</p>
                    <time class="time">${post.created_at}</time>
                </div>
            </article>
        `).join('');
    }

    renderNoPosts() {
        const postsContainer = document.getElementById('posts-container');
        postsContainer.innerHTML = '<p>Нет доступных постов.</p>';
    }

    renderError(message) {
        const profileInfo = document.getElementById('profile-info');
        profileInfo.innerHTML = `<p>${message}</p>`;
    }

    initPostForm() {
        const form = document.getElementById('post-form');
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const content = form.querySelector('#content').value;
            try {
                const data = await this.fetcher.post('/post.php', { content,
                    user_id: this.currentUserId});
                if (data.success) {
                    form.reset();

                    const postsData = await this.fetcher.post('../../api/api_posts.php', {});
                    const userData = await this.fetcher.post('../../api/api_user.php', {});
                    if (postsData.success && userData.success) {
                        this.renderPosts(postsData.posts, userData.user.id);
                    }
                } else {
                    alert(`Error while publishing: ${data.error || 'Unknown error'}`);
                }
            } catch (error) {
                alert('Error sending post. Try again.');
            }
        });
    }
}