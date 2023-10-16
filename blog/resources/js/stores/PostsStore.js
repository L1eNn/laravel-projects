import {defineStore} from "pinia";
import {ref} from "vue";
import axios from "axios";

export const usePostsStore = defineStore('postsStore', () => {

    const posts = ref([])
    const postIdToEditInLocalStorage = localStorage.getItem('post_id_to_edit')
    const postIdToEdit = ref()
    const postToEdit = ref({})

    const postIdToShowInLocalStorage = localStorage.getItem('post_id_to_show')
    const postIdToShow = ref()
    const postToShow = ref({})

    if (postIdToEditInLocalStorage) {
        postIdToEdit.value = postIdToEditInLocalStorage
    }
    if(postIdToShowInLocalStorage) {
        postIdToShow.value = postIdToShowInLocalStorage
    }

    function getCertainUserPosts(user_id) {
        axios.get(`/api/user/${user_id}/posts`)
            .then(res => {
                posts.value = res.data
            })
    }

    async function getCertainPost() {
        const response = await axios.get(`/api/post/${postIdToEdit.value}`)
        postToEdit.value = response.data
        return response.data
    }

    async function postShow() {
        const response = await axios.get(`/api/post/${postIdToShow.value}/show`)
        postToShow.value = response.data
        return response.data
    }

    function removePostIdToEdit() {
        if (postIdToEditInLocalStorage) {
            postIdToEdit.value = null
            localStorage.removeItem('post_id_to_edit')
        }
    }

    return {
        posts,
        postIdToEdit,
        postToEdit,
        postIdToShow,
        postToShow,
        getCertainUserPosts,
        getCertainPost,
        removePostIdToEdit,
        postShow
    }

})
