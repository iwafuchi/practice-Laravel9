'use strict'

function viewImage() {
    const images = document.querySelectorAll('.image');
    images.forEach(image => {
        image.addEventListener('click', (element) => {
            const imageName = element.target.dataset.id.substr(0, 6);
            const imageId = element.target.dataset.id.replace(imageName + '_', '');
            const imageFile = element.target.dataset.file;
            const imagePath = element.target.dataset.path;

            document.getElementById(imageName + '_thumbnail').src = imagePath + '/' + imageFile;
            document.getElementById(imageName + '_hidden').value = imageId;

            //MicroModal.close(modal);でモーダルを閉じるとimage4が正常に選択できなくなるのでis-openクラスを削除して対応する
            //モーダル表示時にbodyのoverflow属性にhiddenが設定されるので削除する
            const openModal = document.getElementsByClassName('is-open')[0];
            openModal.ariaHidden = true;
            openModal.classList.remove('is-open');
            document.getElementsByTagName('body')[0].style.overflow = '';
        })
    });
}

viewImage();

