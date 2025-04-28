export class RenderTemplate {
    renderTemplate(template, data, toObject = true) {
        let htmlStr = template.replace(/\$\{\s*([^\s\}]+)\s*\}/g, (_, capturedIdentifier) => data[capturedIdentifier] ?? '');
        if (toObject) {
            const fragment = document.createElement('template');
            fragment.innerHTML = htmlStr.trim();
            return fragment.content.firstChild;
        }
        return htmlStr;
    }
}