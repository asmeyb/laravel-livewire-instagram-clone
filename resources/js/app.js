import './bootstrap';

import { livewire_hot_reload } from 'virtual:livewire-hot-reload'
livewire_hot_reload();

import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();


// core version + navigation, pagination modules:
import Swiper from 'swiper';
import { Navigation, Pagination } from 'swiper/modules';

window.Swiper=Swiper;
window.Navigation=Navigation;
window.Pagination=Pagination;

// import Swiper and modules styles
import 'swiper/css';
//import 'swiper/css/navigation';
import 'swiper/css/pagination';

