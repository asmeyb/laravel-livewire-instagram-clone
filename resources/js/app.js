import './bootstrap';

import { livewire_hot_reload } from 'virtual:livewire-hot-reload'
livewire_hot_reload();

import focus from '@alpinejs/focus';
import intersect from '@alpinejs/intersect';

// Livewire v3 manages Alpine automatically, so we hook into it
document.addEventListener('livewire:init', () => {
    window.Alpine.plugin(focus);
    window.Alpine.plugin(intersect);
});


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

