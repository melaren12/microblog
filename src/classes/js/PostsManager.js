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
            const data = await this.fetcher.post('delete.php', { id, type });
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
            const postsData = await this.fetcher.post('../../api/api_posts.php', userId ? { user_id: userId } : {});
            if (postsData.success) {
                return postsData;
            } else {
                return false;
            }
        } catch (error) {
            console.error('Initialization error:', error);
        }
    }

    renderNoPosts() {
        const postsContainer = document.getElementById('posts-container');
        postsContainer.innerHTML = '<p>There are no posts available.</p>';
    }


}