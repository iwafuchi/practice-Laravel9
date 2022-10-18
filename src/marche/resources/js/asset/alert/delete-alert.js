'use strict';

function deleteAlert() {
    const deleteButton = document.getElementById('delete');
    deleteButton.addEventListener('click', () => {
        if (confirm('本当に削除してもよろしいですか？')) {
            document.getElementById('delete_' + deleteButton.dataset.id).submit();
        }
    })
}

deleteAlert();
