<template>
    <div class="container">
        <div class="postCreateForm">
            <label for="title">Title </label>
            <input :class="isTitleValid ? '' : 'inputAlert'" name="title" type="text" placeholder="Title of your new post" v-model="title" required>
            <label for="">Content </label>
            <textarea :class="isContentValid ? '' : 'inputAlert'" name="content" rows="5" placeholder="Content of your new post" v-model="content" required></textarea>
            <div class="imagesDiv">
                <input type="file" v-for="number in imgDivAmount" :key="number" @input="imgDivIncrease">
            </div>
            <button type="submit" @click="sendPost">Create</button>
        </div>
    </div>
</template>

<script>
import router from "../../router.js";
import {useAuthStore} from "../../stores/AuthStore.js";
import {usePostsStore} from "../../stores/PostsStore.js";

export default {
    name: "PostCreateComponent",
    data() {
        return {
            authStore: useAuthStore(),
            postsStore: usePostsStore(),

            title: "",
            isTitleValid: true,
            content: "",
            isContentValid: true,
            images: [],
            imgDivAmount: 1,
        }
    },
    methods: {
        imgDivIncrease($event) {
            if (this.imgDivAmount < 10) {
                if ($event.target.value) {
                    this.images.push($event.target.files[0])
                    this.imgDivAmount++
                }
                else {
                    this.imgDivAmount--
                }
            }
        },

        sendPost() {
            this.isContentValid = this.content.length !== 0;
            if (this.title.length !== 0) {
                this.isTitleValid = true

                let formData = new FormData

                for (let i = 0; i < this.images.length; i++) {
                    formData.append(`image_${i}`, this.images[i])
                }

                formData.append('user_id', this.authStore.user.id)
                formData.append('imageAmount', this.imgDivAmount)
                formData.append('title', this.title)
                formData.append('content', this.content)

                axios.post('/api/posts', formData, {
                    headers: {
                        'Content-Type': "multipart/form-data"
                    }
                }).then( res => {
                    console.log(res)
                    router.push({name: 'profile.index'})
                })
            } else {
                this.isTitleValid = false
            }
        },
    }
}
</script>

<style scoped>

.container {
    background-color: white;
    box-shadow: 0 0 1rem 1px rgba(0, 0, 0, 0.1);
}

.postCreateForm {
    display: flex;
    flex-direction: column;
}

input[type = 'text'] {
    height: 30px;
    outline: none;
    border: 1px solid black;
    box-sizing: border-box;
    margin-bottom: 1rem;
    border-radius: 10px;
    padding: 0 5px;
    font-family: 'Ubuntu', sans-serif;
}

.inputAlert {
    border: 1px solid red !important;
    color: red;
}

textarea {
    resize: none;
    margin-bottom: 1rem;
    outline: none;
    box-sizing: border-box;
    border-radius: 10px;
    padding: 5px;
    border: 1px solid black;
    font-family: 'Ubuntu', sans-serif;
}

button {
    height: 35px;
    width: 80px;
    border: 0;
    background-color: #3d9fff;
    border-radius: 5px;
    font-size: 1rem;
    color: white;
    transition-duration: .2s;
    align-self: end;
    cursor: pointer;
}

button:hover {
    opacity: 80%;
}

</style>
