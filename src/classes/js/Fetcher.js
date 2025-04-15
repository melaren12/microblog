export class Fetcher {
    constructor(baseUrl = '') {
        this.baseUrl = baseUrl;
    }

    async post(url, data, headers = {}) {
        try {
            const response = await fetch(`${this.baseUrl}${url}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    ...headers,
                },
                body: new URLSearchParams(data).toString(),
            });

            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }

            return await response.json();
        } catch (error) {
            console.error('Fetch error:', error);
            throw error;
        }
    }
}