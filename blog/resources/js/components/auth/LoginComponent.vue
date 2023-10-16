<template>
    <div class="container">
        <div class="loginForm">
            <h1>LOGIN</h1>
            <div class="firstBlock">
                <div class="inputField">
                    <i class="fa-solid fa-envelope" style="color: #000000;"></i>
                    <input name="email" type="email" placeholder="Email" required v-model="email">
                </div>
                <div class="inputField">
                    <i class="fa-solid fa-lock" style="color: #000000;"></i>
                    <input name="password" type="password" placeholder="Password" required v-model="password">
                </div>
            </div>
            <div class="secondBlock">
                <label>
                    <input name="remember" type="checkbox" v-model="isRememberClicked">
                    Remember me
                </label>
                <router-link to=""><p>Forgot password</p></router-link>
            </div>
            <div class="thirdBlock">
                <button @click.prevent="login">Login</button>
            </div>
            <div class="forthBlock">
                <router-link :to="{name: 'register'}"><p>Create account</p></router-link>
            </div>
        </div>
    </div>
</template>

<script>

import router from "../../router.js";
import {useAuthStore} from "../../stores/AuthStore.js";

export default {
    name: "LoginComponent",
    data() {
        return {
            authStore: useAuthStore(),

            email: null,
            password: null,
            isRememberClicked: false,
        }
    },

    methods: {
        async getProfileStatusAndRedirect() {
            try {
                const response = await axios.get('/api/auth/user/profile')
                this.authStore.profileStatus = response.data
                router.push({name: 'profile.index'})
            } catch (err) {
                console.log(err)
            }
        },
        login() {
            axios.get('/sanctum/csrf-cookie')
                .then(response => {
                axios.post('/login', {
                    email: this.email,
                    password: this.password,
                }).then(res => {
                    this.authStore.token = res.config.headers['X-XSRF-TOKEN']
                    this.getProfileStatusAndRedirect()
                })
            })
        }
    }
}
</script>

<style scoped>

.container {
    margin: 0 400px;
    height: 300px;
}

.loginForm {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;

    & h1 {
        margin: 0 0 3rem 0;
        font-size: 3rem;
    }
}

.loginForm .firstBlock {
    width: 100%;
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
    margin-bottom: 1rem;
    height: 90px;
}

.loginForm .firstBlock input {
    height: 30px;
    width: 100%;
    box-sizing: border-box;
    font-family: 'Ubuntu', sans-serif;
    font-size: 16px;
    border-radius: 3px;
    padding: 0 10px 0 30px;
    border: 1px solid black;
    transition-duration: .2s;
}

.loginForm .secondBlock {
    width: 100%;
    display: flex;
    justify-content: space-between;
    padding: 0 16px;
    margin-bottom: 1rem;
}

.loginForm .secondBlock input {
    margin: 0;
    cursor: pointer;
}

.loginForm .secondBlock p {
    margin: 0;
}

.loginForm .secondBlock a {
    text-decoration: none;
    color: dodgerblue;
}

.loginForm .thirdBlock {
    width: 100%;
    height: 35px;
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 1rem;
}

.loginForm .thirdBlock button {
    height: 35px;
    width: 100%;
    margin: 0;
    border: 0;
    border-radius: 10px;
    background-color: #5fa2ff;
    color: white;
    font-size: 16px;
    cursor: pointer;
    transition: height 1s, width .5s, opacity 1s;
}

.loginForm .thirdBlock button:hover {
    opacity: 80%;
}

.loginForm .thirdBlock button:active {
    height: 30px;
    width: 97%;
}

.loginForm .forthBlock {
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
}

.loginForm .forthBlock p {
    margin: 0;
}

.loginForm .forthBlock a {
    text-decoration: none;
    color: dodgerblue;
}

i {
    position: absolute;
    margin: 6px 0 0 10px;
}

</style>
