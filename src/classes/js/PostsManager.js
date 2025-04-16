export class PostsManager {
    constructor(fetcher, type) {
        this.fetcher = fetcher;
        this.type = type;
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
}