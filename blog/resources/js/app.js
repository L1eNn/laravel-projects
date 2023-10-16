import './bootstrap';
import { createApp } from 'vue';
import { createPinia } from "pinia";
import router from "./router.js";

const app = createApp({});

import App from './components/App.vue'
import LoginComponent from "./components/auth/LoginComponent.vue";
import RegisterComponent from "./components/auth/RegisterComponent.vue";

app.component('app', App)
app.component('login-component', LoginComponent)
app.component('register-component', RegisterComponent)
app.use(router)

const pinia = createPinia()
app.use(pinia)


app.mount('#app');
