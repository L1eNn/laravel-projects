<template>
    <div class="commentsContainer">
        <div class="comments">
            <CommentComponent v-for="(comment, index) in comments" :key="index"
                              :comment="comment">

            </CommentComponent>
        </div>
        <div class="addPostContainer">
            <div class="inputField">
                <input type="text" v-model="newComment">
            </div>
            <button type="submit" @click="createComment">submit</button>
        </div>
    </div>

</template>

<script>
import CommentComponent from "./CommentComponent.vue";
import {useAuthStore} from "../../stores/AuthStore.js";

export default {
    name: "IndexComponent",
    components: {
        CommentComponent
    },
    data() {
        return {
            authStore: useAuthStore(),

            newComment: '',
        }
    },

    props: {
        comments: null,
        post_id: null,
    },

    methods: {
        createComment() {
            axios.post('/api/comments', {
                'user_id': this.authStore.user.id,
                'post_id': this.post_id,
                'content': this.newComment,
            }).then(res => {
                this.newComment = ''
                this.comments.push(res.data)
            })
        }
    }
}
</script>

<style scoped>

.commentsContainer {
    height: 600px;
    width: 70%;
    background-color: white;
    box-shadow: 0 0 5px 300px rgba(0, 0, 0, 0.6);
    padding: 2rem;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.comments {
    overflow-y: scroll;
    padding-right: 1rem;

    &::-webkit-scrollbar {
        background-color: #f6f6f6;
        width: 10px;
    }
    &::-webkit-scrollbar-thumb {
        border-radius: 10px;
        background-color: #3d9fff;
    }
    &::-webkit-scrollbar-track {
        -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.2);
        border-radius: 10px;
        background-color: #f9f9fd;
    }
}

.addPostContainer {
    width: 100%;
    display: flex;
    gap: 1rem;
    padding-top: 1rem;
    & .inputField {
        width: 100%;
    }
    & input {
        height: 30px;
        width: 100%;
        box-sizing: border-box;
        border-radius: .5rem;
        border: 1px solid black;
        padding: 0 1rem;
    }
    & button {
        width: 100px;
        background-color: #3d9fff;
        border: 2px solid #399dff;
        color: white;
        font-size: 1rem;
        border-radius: .5rem;
        transition-duration: .2s;
        cursor: pointer;
        &:hover {
            background-color: #ffffff;
            color: #3d9fff;
        }
    }
}

</style>
