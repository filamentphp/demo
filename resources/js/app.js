import '../css/app.css';
import '../scss/application.scss';

import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createPinia } from 'pinia';
import Toast from 'vue-toastification'
import 'vue-toastification/dist/index.css'
import { InertiaProgress } from '@inertiajs/progress';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';

const pinia = createPinia();
const toastOptions = {
    position: 'top-center',
    timeout: 3000,
};

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';
createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })

      .use(plugin)
      .use(ZiggyVue, Ziggy)
      .use(pinia)
      .use(Toast, toastOptions)
      .mount(el)
  },
  progress: {
    color: '#03b2ad',
},
})

InertiaProgress.init({
    // The delay after which the progress bar will
  // appear during navigation, in milliseconds.
  delay: 250,

  // The color of the progress bar.
  color: '#03b2ad',

  // Whether to include the default NProgress styles.
  includeCSS: true,

  // Whether the NProgress spinner will be shown.
  showSpinner: true,
})
