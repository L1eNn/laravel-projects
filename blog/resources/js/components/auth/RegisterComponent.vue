<template>
    <div :class="passwordAlertText.length !== 0 || passwordConfirmationAlertText.length !== 0 ? 'longContainer' : 'container'">
        <div class="registerForm">
            <h1>REGISTER</h1>
            <div class="firstBlock">
                <div class="inputField">
                    <i class="fa-solid fa-user" style="color: #000000;"></i>
                    <input :class="isLoginInvalid ? 'inputAlert' : ''" type="text" placeholder="Login" required v-model="login">
                </div>
                <div class="inputField">
                    <i class="fa-solid fa-envelope" style="color: #000000;"></i>
                    <input :class="isEmailInvalid ? 'inputAlert' : ''" type="email" placeholder="Email" required v-model="email">
                </div>
                <div class="inputField">
                    <i class="fa-solid fa-lock" style="color: #000000;"></i>
                    <input :class="passwordAlertText.length !== 0 ? 'inputAlert' : ''" type="password" placeholder="Password" required @input="passwordValidate($event.target.value)">
                </div>
                <div class="inputField">
                    <i class="fa-solid fa-lock" style="color: #000000;"></i>
                    <input :class="passwordConfirmationAlertText.length !== 0 ? 'inputAlert' : ''" type="password" placeholder="Repeat password" required @input="passwordsEqual($event.target.value)">
                </div>
            </div>
            <div class="secondBlock" v-show="passwordAlertText.length !== 0">
                <p>{{ passwordAlertText }}</p>
            </div>
            <div class="thirdBlock" v-show="passwordAlertText.length === 0 && passwordConfirmationAlertText.length !== 0">
                <p>{{ passwordConfirmationAlertText }}</p>
            </div>
            <div class="fourthBlock">
                <button @click.prevent="register">Register</button>
            </div>
            <div class="fifthBlock">
                <router-link :to="{name: 'login'}"><p>Have account</p></router-link>
            </div>
        </div>
    </div>
</template>

<script>

import router from "../../router.js";
import {useAuthStore} from "../../stores/AuthStore.js";
import ProfileCreateComponent from "../profile/CreateComponent.vue";

export default {
    name: "CreateComponent",
    components: {ProfileCreateComponent},
    data() {
        return {
            authStore: useAuthStore(),

            login: "",
            email: "",
            password: "",
            passwordConfirmation: "",
            isLoginInvalid: false,
            isEmailInvalid: false,
            passwordAlertText: "",
            passwordConfirmationAlertText: "",
        }
    },
    methods: {
        loginLengthCheck() {
            this.login.length === 0 ? this.isLoginInvalid = true : this.isLoginInvalid = false
        },

        emailValidate() {
            this.email.length === 0 ? this.isEmailInvalid = true : this.isEmailInvalid = false

            let hasDog = false
            this.email.split('').forEach(char => {
                if (char === '@') {
                    hasDog = true
                }
            })
            this.isEmailInvalid = !hasDog;
        },

        passwordValidate(value) {
            let hasNum = false
            let hasLetter = false
            let hasCapitalLetter = false
            if (value.length === 0) {
                this.passwordAlertText = "Password must be more than 8 characters"
            }
            else {
                this.passwordAlertText = ""
            }
            value.split('').forEach(char => {
                if (value.length > 8) {
                    if (/^[a-z]$/i.test(char) || hasLetter) {
                        hasLetter = true
                        if (char === char.toUpperCase() && isNaN(parseInt(char)) || hasCapitalLetter) {
                            hasCapitalLetter = true
                            this.password = value
                            this.passwordAlertText = ""
                        } else {
                            this.passwordAlertText = "Password must contain at least one capital letter"
                        }
                    } else {
                        this.passwordAlertText = "Password must contain letters"
                    }
                    if (!isNaN(parseInt(char)) || hasNum) {
                        hasNum = true
                    } else {
                        this.passwordAlertText = "Password must contain numbers"
                    }
                } else {
                    this.passwordAlertText = "Password must be more than 8 characters"
                }
            })
        },

        passwordsEqual(value) {
            if (this.password === value) {
                this.passwordConfirmationAlertText = ""
                this.passwordConfirmation = value
            } else {
                this.passwordConfirmationAlertText = "Passwords are not equal"
            }
        },

        register() {
            this.loginLengthCheck()
            this.emailValidate()
            if (!this.isLoginInvalid && !this.isEmailInvalid) {
                if (this.password.length > 8) {
                    if (this.password === this.passwordConfirmation) {
                        axios.get('/sanctum/csrf-cookie')
                            .then(response => {
                                axios.post('/register', {
                                    name: this.login,
                                    email: this.email,
                                    password: this.password,
                                    password_confirmation: this.passwordConfirmation
                                }).then(res => {
                                    this.authStore.token = res.config.headers['X-XSRF-TOKEN']
                                    router.push({name: 'profile.create'})
                                })
                            })
                    }
                } else {
                    this.passwordAlertText = "Password must be more than 8 characters"
                }
            }
        }
    }
}
</script>

<style scoped>

.container {
    margin: 0 400px;
    height: 350px;
}

.longContainer {
    background-color: white;
    box-shadow: 0 0 1rem 1px rgba(0, 0, 0, 0.075);
    padding: 50px;
    margin: 0 400px;
    height: 370px;
}

.registerForm {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;

    & h1 {
        margin: 0 0 3rem 0;
        font-size: 3rem;
    }
}

.registerForm .firstBlock {
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
    margin-bottom: 1rem;
    height: 190px;
    width: 100%;
}

.registerForm .firstBlock input {
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

.registerForm .firstBlock .inputAlert {
    height: 30px;
    border: 1px solid red;
    transition-duration: .2s;
    color: red;
}

.registerForm .firstBlock .inputAlert:focus {
    border: 2px solid red;
}
.registerForm .secondBlock, .thirdBlock {
    display: flex;
    justify-content: center;
    margin-bottom: .5rem;
    width: 100%;
}

.registerForm .secondBlock p, .thirdBlock p {
    margin: 0;
    color: #ff0000;
}

.registerForm .fourthBlock {
    height: 35px;
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 1rem;
}

.registerForm .fourthBlock button {
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

.registerForm .fourthBlock button:hover {
    opacity: 80%;
}

.registerForm .fourthBlock button:active {
    height: 30px;
    width: 97%;
}

.registerForm .fifthBlock {
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
}

.registerForm .fifthBlock p {
    margin: 0;
}

.registerForm .fifthBlock a {
    text-decoration: none;
    color: dodgerblue;
}

i {
    position: absolute;
    margin: 6px 0 0 10px ;
}

</style>
