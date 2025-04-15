export class DeleteManager {
    constructor(fetcher) {
        this.fetcher = fetcher;
    }

    initDeleteItems(selector, type, itemClass) {
        const buttons = document.querySelectorAll(selector);
        buttons.forEach(button => {
            button.addEventListener('click', async (e) => {
                e.preventDefault();
                const id = button.getAttribute('data-id');
                const element = button.closest(itemClass);
                await this.deleteItem(id, type, element);
            });
        });
    }

    async deleteItem(id, type, element) {
        try {
            const data = await this.fetcher.post('delete.php', { id, type });
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