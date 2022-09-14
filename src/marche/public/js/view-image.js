'use strict'

function viewImage() {
    const images = document.querySelectorAll('.image');
    console.log(images);
    images.forEach(image => {
        image.addEventListener('click', (element) =>{
            const imageName = element.target.dataset.id.substr(0, 6);
            const imageId = element.target.dataset.id.replace(imageName + '_', '');
            const imageFile = element.target.dataset.file;
            const imagePath = element.target.dataset.path;
            const modal = element.target.dataset.modal;

            document.getElementById(imageName + '_thumbnail').src = imagePath + '/' + imageFile;
            document.getElementById(imageName + '_hidden').value = imageId;
            MicroModal.close(modal);
        })
    });
}

viewImage();

