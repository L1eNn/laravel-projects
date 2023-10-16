<template>
    <div class="post">
        <div class="postTopInfo">
            <p>{{ post.user.name }}</p>
            <p>{{ postCreatedDate }}</p>
        </div>
        <div class="postContent">
            <router-link :to="{name: 'posts.show'}"><h1 @click="showPostClicked">{{ post.title }}</h1></router-link>
            <p>{{ post.content }}</p>
            <div class="images" v-if="images.length">
                <div class="column">
                    <img :src="'/images/'+postCreatedDate+ ' ' + image" alt="" v-for="(image, index) in imageFirstColumn" :key="index">
                </div>
                <div class="column" v-if="imageSecondColumn.length !== 0">
                    <img :src="'/images/'+postCreatedDate+ ' ' + image" alt="" v-for="(image, index) in imageSecondColumn" :key="index">
                </div>
                <div class="column" v-if="imageThirdColumn.length !== 0">
                    <img :src="'/images/'+postCreatedDate+ ' ' + image" alt="" v-for="(image, index) in imageThirdColumn" :key="index">
                </div>
            </div>
        </div>
        <div class="postBottomInfo">
            <div class="likes">
                <i :class="isLiked ? 'fa-solid fa-heart fa-lg' : 'fa-regular fa-heart fa-lg'" style="color: #000000;" @click="likeButtonClicked"></i>
                <p>{{ likesAmount }}</p>
            </div>
            <div class="comments">
                <i class="fa-regular fa-comment fa-lg" style="color: #000000;" @click="commentsButtonClicked"></i>
                <p>{{ commentsAmount }}</p>
            </div>
            <div class="views">
                <i class="fa-regular fa-eye fa-lg" style="color: #000000;"></i>
                <p>{{ viewsAmount }}</p>
            </div>
        </div>
        <div class="commentsBackground" @click="commentsButtonClicked" v-show="commentsWindowActive">
        </div>
        <index-component class="commentsIndex" v-show="commentsWindowActive"
                         :comments="post.comments"
                         :post_id="post.id">

        </index-component>
    </div>
</template>

<script>
import IndexComponent from "../comments/IndexComponent.vue";
import {useAuthStore} from "../../stores/AuthStore.js";
import {usePostsStore} from "../../stores/PostsStore.js";

export default {
    name: "PostComponent",
    components: {
        IndexComponent
    },
    data() {
        return {
            authStore: useAuthStore(),
            postsStore: usePostsStore(),

            images: [],
            imageFirstColumn: [],
            imageSecondColumn: [],
            imageThirdColumn: [],

            postCreatedDate: '',

            isLiked: false,
            likesArr: [],
            like: {},
            likesAmount: null,

            commentsWindowActive: false,
            commentsAmount: null,

            viewsAmount: null,

            isMoreButtonActive: false,
            isDeleteButtonClicked: false,
        }
    },
    props: {
        post: {},
    },
    mounted() {
        this.createdAtTransform()

        this.images = JSON.parse(this.post.images)
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

        this.likesAmount = this.post.likes.length
        this.defineLike()

        this.commentsAmount = this.post.comments.length

        this.viewsAmount = this.post.views.length
    },
    methods: {
        createdAtTransform() {
            this.postCreatedDate = this.post.created_at.substring(0, 10) + ' ' + this.post.created_at.substring(11, 19).replaceAll(':', '-')
        },

        defineLike() {
            this.likesArr = this.post.likes
            this.likesArr.forEach(like => {
                if (like['user_id'] === this.authStore.user.id && like['post_id'] === this.post.id) {
                    this.isLiked = true
                    this.likeImgSrc = "active-heart.svg"
                    this.like = like
                }
            })
        },

        likeButtonClicked() {
            if (this.isLiked) {
                this.isLiked = false
                this.likesAmount--

                axios.delete(`/api/likes/${this.like.id}`)
                    .then(res => {
                        this.like = {}
                    })
            }
            else {
                this.isLiked = true
                this.likesAmount++

                let formData = new FormData
                formData.append('user_id', this.authStore.user.id)
                formData.append('post_id', this.post.id)

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

        commentsButtonClicked() {
            this.commentsWindowActive = !this.commentsWindowActive
        },

        moreButtonClicked() {
            this.isMoreButtonActive = !this.isMoreButtonActive
            if (!this.isMoreButtonActive) {
                this.isDeleteButtonClicked = false
            }
        },

        showPostClicked() {
            localStorage.setItem('post_id_to_show', this.post.id.toString())
            this.postsStore.postIdToShow = this.post.id
        },

        editPostClicked() {
            localStorage.setItem('post_id_to_edit', this.post.id.toString())
            this.postsStore.postIdToEdit = this.post.id
        },

        postDelete() {
            this.isDeleteButtonClicked = false
            this.isMoreButtonActive = false
            axios.delete(`/api/posts/${this.post['id']}`)
                .then(res => {
                    console.log(res)
                    let postIndex = this.postsStore.posts.indexOf(this.post)
                    this.postsStore.posts.splice(postIndex, 1)
                })
        }
    }
}
</script>

<style scoped>

a {
    text-decoration: none;
    color: black;
}

.post {
    margin-bottom: 3rem;
}

.postTopInfo {
    display: flex;
    justify-content: space-between;
}

.postTopInfo p {
    margin: 0;
}

.postContent {
    margin-bottom: 1rem;
    & p {
        margin: 0 0 1rem 0;
    }
}

.postBottomInfo {
    display: flex;
    justify-content: space-between;
    & .likes, .comments {
        display: flex;
        align-items: center;
        gap: 3px;
        margin: 0;
        & i {
            cursor: pointer;
            transition-duration: .2s;
            &:active {
                transform: scale(.8);
            }
        }
    }
    & .views {
        display: flex;
        align-items: center;
        gap: 3px;
        margin: 0;
    }
    & .comments {
        flex: 1;
        margin-left: 2rem;
    }
}

.postBottomInfo p {
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

.commentsBackground {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}

.commentsIndex {
    position: fixed;
    top: 15%;
    left: 15%;
}

</style>
