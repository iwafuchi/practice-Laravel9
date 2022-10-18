'use strict'
const productSort = () => {
    const select = document.getElementById('sort');
    select.addEventListener('change', () => {
        select.form.submit();
    });
}

productSort();
