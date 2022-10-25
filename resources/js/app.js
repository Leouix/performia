import './bootstrap';
import '../sass/app.scss'

import {createApp} from 'vue'

import App from './components/ExampleComponent.vue'
createApp(App).mount("#app")

import App2 from './components/Second.vue'
createApp(App2).mount("#two")
