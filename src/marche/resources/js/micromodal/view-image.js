'use strict'

export function viewImage() {
    const images = document.querySelectorAll('.image') //全てのimageタグを取得
    images.forEach(image => {
        image.addEventLister('click', function(e) {
            const imageName = e.target.dataset.id.substr(0, 6);
            const imageId = e.target.dataset.id.replace(imageName + '_', '');
            const imageFile = e.target.dataset.file;
            const imagePath = e.target.dataset.path;
            const modal = e.target.dataset.modal;

            document.getElementById(imageName + '_thumbnail').src = imagePath + '/' + imageFile;
            document.getElementById(imageName + '_hidden').value = imageId;
            Micromodal.close(modal);
        })
    });
}
