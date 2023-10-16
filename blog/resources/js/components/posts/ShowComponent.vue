<template>
    <div class="post">
        <div v-show="isImageFullScreen" class="imageFullScreenContainer" >
            <div class="fullScreenImageBackground" @click="closeFullScreenImage"></div>
            <div class="fullScreenImage">
                <img :src="fullScreenImageSrc" alt="" class="fullScreenImage">
            </div>
        </div>

        <div class="postTopInfo">
            <p>{{ postAuthor }}</p>
            <p>{{ postCreatedDate }}</p>
        </div>
        <div class="postContent">
            <h1>{{ postsStore.postToShow.title }}</h1>
            <p>{{ postsStore.postToShow.content }}</p>
            <div class="images" v-if="images.length">
                <div class="column">
                    <img :src="'/images/'+postCreatedDate+ ' ' + image" alt="" v-for="(image, index) in imageFirstColumn" :key="index" @click="imageToFullScreen">
                </div>
                <div class="column" v-if="imageSecondColumn.length !== 0">
                    <img :src="'/images/'+postCreatedDate+ ' ' + image" alt="" v-for="(image, index) in imageSecondColumn" :key="index" @click="imageToFullScreen">
                </div>
                <div class="column" v-if="imageThirdColumn.length !== 0">
                    <img :src="'/images/'+postCreatedDate+ ' ' + image" alt="" v-for="(image, index) in imageThirdColumn" :key="index" @click="imageToFullScreen">
                </div>
            </div>
        </div>
        <div class="postBottomInfo">
            <div class="likes">
                <img :src="'/icons/'+likeImgSrc" alt="" @click="likeImageClicked">
                <p>{{ likesAmount }}</p>
            </div>
            <div class="views">
                <img src="/icons/eye.svg" alt="">
                <p>{{ viewsAmount }}</p>
            </div>
        </div>
    </div>
</template>

<script>

import {useAuthStore} from "../../stores/AuthStore.js";
import {usePostsStore} from "../../stores/PostsStore.js";

export default {
    name: "PostShowComponent",
    data() {
        return {
            authStore: useAuthStore(),
            postsStore: usePostsStore(),

            postAuthor: '',

            images: [],
            imageFirstColumn: [],
            imageSecondColumn: [],
            imageThirdColumn: [],

            postCreatedDate : '',

            likeImgSrc: 'heart.svg',
            isLiked: false,
            likesArr: [],
            like: {},
            likesAmount: null,

            viewsAmount: null,

            fullScreenImageSrc: '',
            isImageFullScreen: false,
        }
    },

    mounted() {
        this.getUserName()

        this.createdAtTransform()

        this.images = JSON.parse(this.postsStore.postToShow.images)
        this.images.forEach((image, index) => {
            if (index % 3 === 0) {
                this.imageThirdColumn.push(image)
            }
            else if (index % 3 === 1) {
                this.imageSecondColumn.push(image)
            }
            else {
                this.imageFirstColumn.push(image)
            }
        })

        this.likesAmount = this.postsStore.postToShow.likes.length
        this.defineLike()

        this.viewsAmount = this.postsStore.postToShow.views.length
    },
    methods: {
        getUserName() {
            axios.get(`/api/user/${this.postsStore.postToShow.user_id}`)
                .then(res => {
                    this.postAuthor = res.data.name
                })
        },

        createdAtTransform() {
            this.postCreatedDate = this.postsStore.postToShow.created_at.substring(0, 10) + ' ' + this.postsStore.postToShow.created_at.substring(11, 19).replaceAll(':', '-')
        },

        defineLike() {
            this.likesArr = this.postsStore.postToShow.likes
            this.likesArr.forEach(like => {
                if (like['user_id'] === this.authStore.user.id && like['post_id'] === this.postsStore.postToShow.id) {
                    this.isLiked = true
                    this.likeImgSrc = "active-heart.svg"
                    this.like = like
                }
            })
        },

        likeImageClicked() {
            if (this.isLiked) {
                this.isLiked = false
                this.likeImgSrc = "heart.svg"
                this.likesAmount--

                axios.delete(`/api/likes/${this.like['id']}`)
                    .then(res => {
                        console.log(res)
                    })

            }
            else {
                this.isLiked = true
                this.likeImgSrc = "active-heart.svg"
                this.likesAmount++

                let formData = new FormData
                formData.append('user_id', this.authStore.user.id)
                formData.append('post_id', this.postsStore.postToShow.id)

                axios.post('/api/likes', formData, {
                    headers: {
                        'Content-Type': "multipart/form-data"
                    }
                })
                    .then( res => {
                        this.like = res.data[0]
                    })
            }
        },

        imageToFullScreen($event) {
            this.fullScreenImageSrc = $event.target.src
            this.isImageFullScreen = true
            document.body.style.overflow = "hidden"
        },

        closeFullScreenImage() {
            this.isImageFullScreen = false
            document.body.style.overflow = "auto"
        }
    }
}
</script>

<style scoped>

.postTopInfo {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.postContent {
    margin-bottom: 1rem;
}

.postBottomInfo {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.postBottomInfo img {
    width: 20px;
    height: 20px;
    transition-duration: .2s;
}

.postBottomInfo img:active {
    transform: scale(.8);
}

.postBottomInfo p {
    margin: 0;
}

.likes, .views {
    display: flex;
    align-items: center;
    gap: 3px;
    margin: 0;
}

.images {
    display: flex;
    gap: 10px;
}

.column {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.column img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.imageFullScreenContainer {
    position: fixed;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 99;
}

.fullScreenImageBackground {
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.9);
    position: fixed;
}

.fullScreenImage {
    height: 90%;
    z-index: 1;
}

.fullScreenImage img {
    width: 100%;
    height: 100%;
    object-fit: contain;
}

</style>
