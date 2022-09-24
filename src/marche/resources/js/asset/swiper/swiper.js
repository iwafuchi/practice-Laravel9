  // core version + navigation, pagination modules:
  import Swiper, { Navigation, Pagination, Scrollbar } from 'swiper';
  // import Swiper and modules styles
  import 'swiper/css';
  import 'swiper/css/navigation';
  import 'swiper/css/pagination';

// document.addEventListener('DOMContentLoaded', () => {
    // init Swiper:
const swiper = new Swiper('.swiper', {
        //モジュールをSwiperで使用可能にする
        modules: [Navigation, Pagination, Scrollbar],

        // Optional parameters
        direction: 'horizontal',
        loop: true,

        // If we need pagination
        pagination: {
            el: '.swiper-pagination',
            type: 'bullets',
            clickable: true,
        },

        // Navigation arrows
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },

        // And if we need scrollbar
        scrollbar: {
            el: '.swiper-scrollbar',
        },
        observer: true,
        observeParents: true,
        parallax: true,
    });
// })
