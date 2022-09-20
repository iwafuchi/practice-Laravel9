'use strict';
function deletePost() {
    const deleteButton = document.getElementById('delete');
    deleteButton.addEventListener('click', () => {
        if (confirm('本当に削除してもよろしいですか？')) {
            document.getElementById('delete_' + deleteButton.dataset.id).submit();
        }
    })
}

deletePost();
