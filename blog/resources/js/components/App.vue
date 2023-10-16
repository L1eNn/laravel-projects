<template>
    <header>
        <router-link :to="{name: 'posts.index'}"><h1 @click="isPopUpVisible = false">Blog</h1></router-link>
        <div class="nav">
            <div class="loggedOptions" v-if="authStore.token">
                <div @click="isPopUpVisible = !isPopUpVisible" class="userNameContainer">
                    <p>{{ authStore.user.name }}</p>
                    <img :src="'/icons/'+userTriangleSrc" alt="">
                </div>
                <div class="profilePopUp" v-if="isPopUpVisible">
                    <div @click="logout" class="logoutOption">
                        <p>Logout</p>
                    </div>
                    <div class="profileOption">
                        <router-link :to="{name: 'profile.index'}"><p @click="isPopUpVisible = false">Profile</p></router-link>
                    </div>
                </div>
            </div>
            <div class="guestOptions" v-else>
                <router-link :to="{name: 'login'}"><p>Log in</p></router-link>
            </div>
        </div>
    </header>
    <main>
        <div class="content">
            <router-view></router-view>
        </div>
    </main>
</template>

<script>

import router from "../router.js";
import {useAuthStore} from "../stores/AuthStore.js";
import axios from "axios";

export default {
    name: "App",
    data() {
        return {
            authStore: useAuthStore(),

            isPopUpVisible: false,
            userTriangleSrc: "downward-triangle.svg",
            user: {}
        }
    },

    watch: {
        isPopUpVisible(value, oldValue) {
            if (value) {
                this.userTriangleSrc = 'upward-triangle.svg'
            }
            else {
                this.userTriangleSrc = 'downward-triangle.svg'
            }
        }
    },

    methods: {
        logout() {
            this.isPopUpVisible = false
            axios.post('/logout')
                .then(res => {
                    this.authStore.token = ""
                    this.authStore.profileStatus = false
                    router.push({name: 'posts.index'})
                });
        }
    }
}
</script>

<style scoped>

header {
    height: 80px;
    background-color: white;
    box-shadow: 0 0 1rem 1px rgba(0, 0, 0, 0.1);
    display: flex;
    padding: 0 10%;
    align-items: center;
    justify-content: space-between;
}

header h1 {
    font-family: 'Kanit', sans-serif;
}

header h1, header p {
    margin: 0;
}

header .nav p {
    cursor: pointer;
}

a {
    text-decoration: none;
    color: black;
}

.loggedOptions .userNameContainer {
    display: flex;
    align-items: center;
    justify-content: center;
}

.loggedOptions .userNameContainer img {
    margin: 0;
    cursor: pointer;
    height: 25px;
    width: 25px;
}

.profilePopUp {
    position: fixed;
    margin-top: 5px;
    background-color: white;
    box-shadow: 0 0 1rem 1px rgba(0, 0, 0, 0.1);
    display: flex;
    flex-direction: column;
    border-radius: 10px;
}

.profilePopUp .logoutOption, .profileOption {
    padding: 1rem;
    cursor: pointer;
    background-color: white;
    transition-duration: .2s;
}

.profilePopUp .logoutOption {
    border-radius: 10px 10px 0 0;
}

.profilePopUp .profileOption {
    border-radius: 0 0 10px 10px;
}

.profilePopUp .logoutOption:hover, .profileOption:hover {
    background-color: #e8e8e8;
}

</style>
