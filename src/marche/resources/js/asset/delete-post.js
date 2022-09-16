'use strict';
export const deletePost = (e) =>{
    if (confirm('本当に削除してもよろしいですか？')) {
        // console.log(e.getAttribute('data-id'));
        // console.log(e.dataset.id);
        document.getElementById('delete_1').submit();
        // document.getElementById('delete_' + e.dataset.id).submit();
    }
}

export const testAlert = () => {
    alert("test");
}

// const button = document.getElementById('test');
// alert(button);
// button.addEventListener('click', deletePost);
// export function deletePost(e) {
//     if (confirm('本当に削除してもよろしいですか？')) {
//         document.getElementById('delete_' + e.dataset.id).submit();
//     }
// }
