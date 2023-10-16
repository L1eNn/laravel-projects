<template>
    <div class="container">
        <div class="profile">
            <div class="userInfo">
                <div class="mainInfoContainer">
                    <div class="avatarContainer">
                        <img :src="avatarSrc" alt="">
                    </div>
                    <div class="userName">
                        <h1>{{ profileInfo.first_name }}</h1>
                        <h1>{{ profileInfo.second_name }}</h1>
                    </div>
                </div>
                <div class="otherInfoContainer">
                    <div class="imageButtonContainer" :class="isOtherInfoActive ? 'sun' : 'circle' ">
                        <img :src="'/icons/' + otherInfoButtonSrc" alt="" @click="otherInfoButtonClicked">
                    </div>
                    <div class="info" v-if="isOtherInfoActive">
                        <h3>Birthday: </h3>
                        <p>{{ profileInfo.birthday}}</p>
                        <h3>Country: </h3>
                        <p>{{ profileInfo.country }}</p>
                        <h3>City: </h3>
                        <p>{{ profileInfo.city }}</p>
                    </div>
                </div>
            </div>
            <div class="actions">
                <router-link :to="{name: 'post.create'}"><button id="newPostButton">new post</button></router-link>
            </div>
            <div class="posts" v-for="(post, index) in postsStore.posts" :key="index">
                    <post-component :post="post">

                    </post-component>
            </div>
        </div>
    </div>
</template>

<script>
import PostComponent from "./PostComponentReserve.vue";
import {useAuthStore} from "../../stores/AuthStore.js";
import {usePostsStore} from "../../stores/PostsStore.js";

export default {
    name: "IndexComponent",
    components: {PostComponent},
    data() {
        return {
            authStore: useAuthStore(),
            postsStore: usePostsStore(),

            profileInfo: {},
            avatarSrc: null,
            isOtherInfoActive: false,
            otherInfoButtonSrc: 'circle.svg',
        }
    },

    mounted() {
        this.getUserProfile()
    },

    methods: {
        otherInfoButtonClicked() {
            if (this.isOtherInfoActive) {
                this.isOtherInfoActive = false
                this.otherInfoButtonSrc = 'circle.svg'
            } else {
                this.isOtherInfoActive = true
                this.otherInfoButtonSrc = 'sun.svg'
            }
        },

        getUserProfile() {
            axios.get('/api/profile')
                .then(res => {
                    this.profileInfo = res.data['profileInfo']
                    this.avatarSrc = res.data['avatarSrc']
                    this.postsStore.getCertainUserPosts(res.data.profileInfo['user_id'])
                })
        },
    }
}
</script>

<style scoped>

.container {
    background-color: white;
    box-shadow: 0 0 1rem 1px rgba(0, 0, 0, 0.075);
    padding: 2rem;
}

.profile .userInfo {
    display: grid;
    grid-template-columns: .3fr 1fr;
}

.profile .userInfo .avatarContainer {
    width: 300px;
    height: 300px;
}

.profile .userInfo .avatarContainer img {
    width: 300px;
    height: 300px;
    border-radius: 100%;
}

.profile .userInfo .mainInfoContainer .userName {
    display: flex;
    gap: 1rem;
    justify-content: center;
}

.profile .userInfo .otherInfoContainer .imageButtonContainer {
    height: 50px;
    width: 50px;
    display: flex;
    &.sun {
        width: 50px;
        height: 50px;
    }
    &.circle {
        justify-content: center;
        align-items: center;
        & img {
            width: 26px;
            height: 26px;
        }
    }
}

.profile .userInfo .otherInfoContainer .info {
    display: grid;
    grid-template-columns: 0fr 1fr;
    grid-column-gap: 1rem;
    grid-row-gap: 1rem;
    margin-left: 5rem;
    align-items: center;
    & h3, p{
        margin: 0;
    }
}

.profile .actions {
    display: flex;
    justify-content: end;
    margin-bottom: 2rem;
}

.profile .actions button {
    height: 45px;
    width: 100px;
    border: 0;
    background-color: #3d9fff;
    border-radius: 5px;
    font-size: 1rem;
    color: white;
    transition-duration: .2s;
    cursor: pointer;
}

.profile .actions button:hover {
    opacity: 80%;
}

</style>
