class OffersTable {

    constructor() {
        this.#initEvents();
    }

    #initEvents() {
        const $this = this;

        Array.from(document.getElementsByClassName('js-btn-delete')).forEach(function(elem) {
            elem.addEventListener('click', (e) => $this.#onClickDelete(e));
        });
    }

    #onClickDelete(event) {
        event.preventDefault();
        const result = confirm('Do you want delete offer?'),
            element = event.currentTarget,
            offersStoreUrl = document.getElementById('js-offers-index-url')
                .getAttribute('value');

        if(!result) {
            return;
        }

        axios.post(element.getAttribute('href'), {'_method': 'DELETE'})
            .then(() => {
                setTimeout(() => window.location.href = offersStoreUrl, 1000);
            })
    }
}

window.onload = (event) => {
    if (document.getElementById('offers-table')) {
        new OffersTable();
    }
};
