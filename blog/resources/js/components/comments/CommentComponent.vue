<template>
    <div class="comment">
        <div class="topBar">
            <div class="commentInfo">
                <p>{{ author }}</p>
                <p>{{ createdDate }}</p>
            </div>
            <div class="actions" v-if="isCommentOfUser">
                <div class="popup" :class="isMoreButtonActive ? 'active' : '' ">
                    <img src="/icons/pen.svg" alt="">
                    <img src="/icons/trash-can.svg" alt="" @click="deleteComment">
                </div>
                <img src="/icons/more.svg" alt="" @click="isMoreButtonActive = !isMoreButtonActive">
            </div>
        </div>
        <div class="commentContent">
            <p>{{ comment.content }}</p>
        </div>
    </div>
</template>

<script>

import {usePostsStore} from "../../stores/PostsStore.js";
import {useAuthStore} from "../../stores/AuthStore.js";

export default {
    name: "CommentComponent",
    data() {
        return {
            authStore: useAuthStore(),
            postsStore: usePostsStore(),

            author: null,
            createdDate: null,
            isCommentOfUser: true,
            isMoreButtonActive: false,
        }
    },
    props: {
        comment: {}
    },

    mounted() {
        this.getCommentAuthorName()
        this.createdAtTransform()
        this.isCommentOfUser = this.comment.user_id === this.authStore.user.id;
    },

    methods: {
        createdAtTransform() {
            this.createdDate = this.comment.created_at.substring(0, 10) + ' ' + this.comment.created_at.substring(11, 19).replaceAll(':', '-')
        },

        getCommentAuthorName() {
            axios.get(`/api/user/${this.comment.user_id}`)
                .then(res => {
                    this.author = res.data.name
                })
        },

        deleteComment() {
            axios.delete(`/api/comments/${this.comment.id}`)
                .then(res => {
                    console.log(res)
                    this.postsStore.getCertainUserPosts(res.data)
                    this.isMoreButtonActive = false
                })
        }
    }
}
</script>

<style scoped>

.comment {
    border-bottom: 1px solid black;
    display: flex;
    flex-direction: column;
    gap: .5rem;
    padding-bottom: 1rem;
}

.comment .topBar {
    display: flex;
    justify-content: space-between;
    height: 2rem;
}

.comment .topBar .actions {
    display: flex;
    align-items: center;
    gap: .5rem;
}

.comment .topBar .actions img {
    height: 2rem;
    width: 2rem;
    cursor: pointer;
    transition-duration: .2s;
    &:active {
        transform: scale(.8);
    }
}

.comment .topBar .actions .popup {
    display: flex;
    gap: .5rem;
    transition-duration: .2s;
    opacity: 0;
    transform: scale(0);
    & img {
        height: 1rem;
        width: 1rem;
    }
}

.comment .topBar .actions .popup.active {
    transform: scale(1);
    opacity: 1;
}

.commentInfo {
    display: flex;
    align-items: center;
    gap: 1rem;
    & p:first-child {
        font-size: 14px;
        font-weight: bold;
    }
    & p:last-child {
        opacity: 0.8;
        font-size: 12px;
    }
    & p {
        margin: 0;
    }
}

.commentContent {
    & p {
        font-size: 18px;
        margin: 0;
    }
}

</style>
