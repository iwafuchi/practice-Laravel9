function deletePost(e) {
    'use strict';
    if (confirm('本当に削除してもよろしいですか？')) {
        document.getElementById('delete_' + e.dataset.id).submit();
    }
}
