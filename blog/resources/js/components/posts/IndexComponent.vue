<template>
    <div class="container">
        <div class="posts">
            <div class="postContainer" v-for="(post, index) in posts" :key="index">
                <post-component
                :post="post">

                </post-component>
            </div>
        </div>
    </div>
</template>

<script>

import {usePostsStore} from "../../stores/PostsStore.js";
import {useAuthStore} from "../../stores/AuthStore.js";
import PostComponent from "./PostComponent.vue";

export default {
    name: "IndexComponent",
    components: {PostComponent},
    data() {
        return {
            postsStore: usePostsStore(),
            authStore: useAuthStore(),
            posts: [],
        }
    },
    mounted() {
        this.getPosts()
    },
    methods: {
        getPosts() {
            axios.get('/api/posts')
                .then(res => {
                    this.posts = res.data
                })
        }
    }
}
</script>

<style scoped>

</style>
