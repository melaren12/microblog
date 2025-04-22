export class PostsManager {
    constructor(fetcher, type) {
        this.fetcher = fetcher;
        this.type = type;
        this.currentUserId = null;
    }

    initDeletePosts(selector, itemClass) {
        const buttons = document.querySelectorAll(selector);
        buttons.forEach(button => {
            button.addEventListener('click', async (e) => {
                e.preventDefault();
                const id = button.getAttribute('data-id');
                const element = button.closest(itemClass);
                await this.deletePost(id, this.type, element);
            });
        });
    }

    async deletePost(id, type, element) {
        try {
            const data = await this.fetcher.post('delete.php', {id, type});
            if (data.success) {
                element.remove();
            } else {
                alert(`Error deleting ${this.type}: ${data.error || 'Unknown error'}`);
            }
        } catch (error) {
            alert(`Error deleting ${this.type}. Please try again.`);
        }
    }

    async initPostsData(userId) {
        try {
            const postsData = await this.fetcher.post('../../api/api_posts.php', userId ? {user_id: userId} : {});
            if (postsData.success) {
                return postsData;
            } else {
                return false;
            }
        } catch (error) {
            console.error('Initialization error:', error);
        }
    }

    initPostForm(userId) {
        const form = document.getElementById('post-form');
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const content = form.querySelector('#content').value;
            try {
                const data = await fetcher.post('post.php', {
                    content,
                    user_id: userId
                });
                if (data.success) {
                    form.reset();
                    const postsData = await fetcher.post('../../api/api_posts.php', {});
                    const userData = await fetcher.post('../../api/api_user.php', {});
                    if (postsData.success && userData.success) {

                        renderPosts(postsData.posts, userData.user.id);
                    }
                } else {
                    alert(`Error while publishing: ${data.error || 'Unknown error'}`);
                }
            } catch (error) {
                alert('Error sending post. Try again.');
                console.error(error);
            }
        });
    }
}