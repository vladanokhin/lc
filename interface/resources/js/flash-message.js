
class FlashMessage {

    wrap = 'flash-message';

    wrapElement = null;

    constructor() {
        this.wrapElement = document.getElementById(this.wrap);
        this.#initEvents();
    }

    #initEvents() {
        document.getElementById('js-btn-close-flash')
                .addEventListener('click', () => this.#onClickClose());
    }

    #onClickClose() {
        this.wrapElement.style.display = 'none';
    }
}

$(document).ready(() => {
    if (document.getElementById('flash-message')) {
        new FlashMessage();
    }
});

