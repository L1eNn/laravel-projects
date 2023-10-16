<template>
    <div class="profileCreateForm">
        <div class="profileInputs">
            <img :src="avatarSrc" alt="">
            <input id="imageInput" name="avatar" type="file" @change="avatarSrcChange">
            <p>First name</p>
            <input :class="isFirstNameValid ? '' : 'inputAlert'" name="firstName" type="text" v-model="firstName" required>
            <p>Second name</p>
            <input :class="isSecondNameValid ? '' : 'inputAlert'" name="secondName" type="text" v-model="secondName" required>
            <p>Birthday</p>
            <input name="date" type="date" v-model="birthday" required>
            <p>Country</p>
            <select name="country" v-model="selectedCountry">
                <option v-for="(country, index) in apiCountries" :key="index" :value="country['name']['common']">{{ country['name']['common'] }}</option>
            </select>
            <p>City</p>
            <input name="city" type="text" v-model="city">
        </div>
        <button type="submit" @click.prevent="createProfile">Create</button>
    </div>
</template>

<script>

import router from "../../router.js";
import {useAuthStore} from "../../stores/AuthStore.js";

export default {
    name: "ProfileCreateComponent",
    data() {
        return {
            authStore: useAuthStore(),

            apiCountries: '',
            avatarSrc: '/icons/no-avatar.png',
            avatar: '',
            firstName: '',
            isFirstNameValid: true,
            secondName: '',
            isSecondNameValid: true,
            birthday: '',
            selectedCountry: '',
            city: '',
        }
    },

    mounted() {
        this.getCountries()
    },

    methods: {
        getCountries() {
            axios.get('https://restcountries.com/v3.1/all?fields=name')
                .then(res => {
                    this.apiCountries = res.data
                })
        },

        avatarSrcChange($event){
            let file = $event.target.files[0]
            this.avatarSrc = URL.createObjectURL(file)
            this.avatar = file
        },

        createProfile() {
            this.isFirstNameValid = this.firstName.length !== 0;
            this.isSecondNameValid = this.secondName.length !== 0;
            let id
            axios.get('/api/latest/user/id')
                .then(res => {
                    id = res.data
                })
            if (this.isFirstNameValid && this.isSecondNameValid) {
                let formData = new FormData
                if (toString(this.authStore.user.id).length !== 0) {
                    formData.append('user_id', this.authStore.user.id)
                } else {
                    formData.append('user_id', id)
                }

                console.log(id)

                formData.append('avatar', this.avatar)
                formData.append('firstName', this.firstName)
                formData.append('secondName', this.secondName)
                formData.append('birthday', this.birthday)
                formData.append('country', this.selectedCountry)
                formData.append('city', this.city)

                axios.post('/api/profile/store', formData, {
                    headers: {
                        'Content-Type': "multipart/form-data"
                    }
                }).then(res => {
                    this.authStore.profileStatus = true
                    router.push({name: 'profile.index'})
                })
            }
        }
    },
}
</script>

<style scoped>

.profileCreateForm {
    margin: 0 400px;
    display: flex;
    flex-direction: column;
    background-color: white;
    padding: 2rem;
    box-shadow: 0 0 1rem 1px rgba(0, 0, 0, 0.1);
    border-radius: 10%;
}

.profileInputs {
    display: grid;
    grid-template-columns: .5fr 1fr;
    align-items: center;
    grid-row-gap: 1rem;
    margin-bottom: 1rem;
}

.profileInputs p {
    margin: 0;
}

.profileInputs input[type='text'], input[type='date'], select {
    width: 100%;
    box-sizing: border-box;
    border: 1px solid black;
    height: 30px;
    border-radius: 10px;
    padding: 0 1rem;
    font-family: 'Ubuntu', sans-serif;
    font-size: 15px;
    outline: none;
}

.profileInputs .inputAlert {
    border: 1px solid red !important;
    color: red;
}

img {
    width: 100px;
    height: 100px;
    border-radius: 100%;
    border: 1px solid black;
    margin-bottom: 1rem;
}

button {
    height: 30px;
    width: 80px;
    align-self: end;
    background-color: #5fa2ff;
    color: white;
    border: 0;
    border-radius: 10px;
    font-family: 'Ubuntu', sans-serif;
    font-size: 14px;
    cursor: pointer;
    transition-duration: .2s;
}

button:hover {
    opacity: .8;
}

#imageInput {
    align-self: end;
}

</style>
