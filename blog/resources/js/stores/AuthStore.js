import {defineStore} from "pinia";
import {ref, watch} from "vue";
import axios from "axios";

export const useAuthStore = defineStore('authStore', () => {
    const token = ref('')
    const tokenInLocalStorage = localStorage.getItem('x_xsrf_token')
    const authenticated = ref(false)
    const user = ref({})
    const profileStatus = ref(false)
    const profileStatusInLocalStorage = localStorage.getItem('profile_status')

    if (tokenInLocalStorage) {
        token.value = tokenInLocalStorage
    }

    if (profileStatusInLocalStorage) {
        profileStatus.value = profileStatusInLocalStorage
    }

    function getAuthUser() {
        axios.get('/api/user')
            .then(res => {
                user.value = res.data
            })
    }

    function getProfileStatus() {
        axios.get('/api/auth/user/profile')
            .then(res => {
                profileStatus.value = res.data
            })
    }

    if (token.value.length !== 0) {
        authenticated.value = true
        getAuthUser()
    }
    else {
        authenticated.value = false
        user.value = {}
    }

    watch(
        ()=> token.value, (newToken) => {
            if (newToken.length !== 0) {
                getAuthUser()
                getProfileStatus()
                localStorage.setItem('x_xsrf_token', newToken)
                authenticated.value = true
            }
            else {
                localStorage.removeItem('x_xsrf_token')
                authenticated.value = false
                user.value = {}
                profileStatus.value = false
            }
    })

    watch(
        () => profileStatus.value, (newProfileStatus) => {
            if (newProfileStatus) {
                getProfileStatus()
                localStorage.setItem('profile_status', newProfileStatus)
            }
            else {
                localStorage.removeItem('profile_status')
            }
        }
    )

    return {
        token,
        authenticated,
        user,
        profileStatus,
    }
})


