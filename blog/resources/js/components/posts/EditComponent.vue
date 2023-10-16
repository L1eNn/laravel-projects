<template>
    <div class="postEditForm">
        <label for="title">Title </label>
        <input name="title" type="text" placeholder="Title of your new post" v-model="title" required>
        <label for="">Content </label>
        <textarea name="content" rows="5" placeholder="Content of your new post" v-model="content" required></textarea>
        <div class="imagesDiv">
            <div :id="number" class="image" v-for="number in imgDivAmount" :key="number">
                <img :src="'/images/'+postCreatedDate+' '+images[number-1]" alt="" v-if="imagesSrc[number-1]=='/images/'">
                <img :src="imagesSrc[number-1]" alt="" v-else>
                <img id="imgDeleteButton" :src="'/icons/cross.svg'" alt="" @click="deleteImage(number)">
            </div>
            <div class="fileInput" v-if="imgDivAmount < 10">
                <label>
                    Add image
                    <img :src="'/icons/add-image.svg'" alt="">
                    <input type="file" accept="image/png, image/jpg, image/jpeg" @input="addImage">
                </label>
            </div>
        </div>
        <button id="submitButton" type="submit" @click.prevent="updatePost">Submit</button>
    </div>

</template>

<script>
import {usePostsStore} from "../../stores/PostsStore.js";

export default {
    name: "PostCreateComponent",
    data() {
        return {
            postsStore: usePostsStore(),

            title: "",
            content: "",
            images: [],
            imagesSrc: [],
            imgDivAmount: 1,
            postCreatedDate: '',
        }
    },
    mounted() {
        this.createdAtTransform()
        this.title = this.postsStore.postToEdit.title
        this.content = this.postsStore.postToEdit.content
        if (JSON.parse(this.postsStore.postToEdit.images)) {
            this.images = JSON.parse(this.postsStore.postToEdit.images)
            this.imgDivAmount = this.images.length
            for (let i = 0; i < this.imgDivAmount; i++) {
                this.imagesSrc.push('/images/')
            }
        }
    },

    methods: {
        createdAtTransform() {
            this.postCreatedDate = this.postsStore.postToEdit.created_at.substring(0, 10) + ' ' + this.postsStore.postToEdit.created_at.substring(11, 19).replaceAll(':', '-')
        },

        addImage($event) {
            this.images.push($event.target.files[0])
            this.imagesSrc.push(URL.createObjectURL(this.images[this.imgDivAmount]))
            this.imgDivAmount++
        },

        deleteImage(index) {
            this.images.splice(index-1, 1)
            this.imagesSrc.splice(index-1, 1)
            this.imgDivAmount -= 1
        },

        updatePost() {
            let formData = new FormData

            for (let i = 0; i < this.images.length; i++) {
                formData.append(`image_${i}`, this.images[i])
            }

            formData.append('imageAmount', this.imgDivAmount)
            formData.append('title', this.title)
            formData.append('content', this.content)
            formData.append('_method', 'PATCH')


            axios.post(`/api/posts/${this.postsStore.postToEdit.id}`, formData, {
                headers: {
                    "Content-Type": "multipart/form-data"
                }
            }).then(res => {
                console.log(res)
                window.location.href="/profile"
            })
        },
    }
}
</script>

<style scoped>

.postEditForm {
    display: flex;
    flex-direction: column;
}

input[type='text'] {
    height: 30px;
    outline: none;
    border: 1px solid black;
    box-sizing: border-box;
    margin-bottom: 1rem;
    border-radius: 10px;
    padding: 0 5px;
    font-family: 'Ubuntu', sans-serif;
}

textarea {
    resize: none;
    height: 200px;
    outline: none;
    border: 1px solid black;
    box-sizing: border-box;
    margin-bottom: 1rem;
    border-radius: 10px;
    padding: 5px;
    font-family: 'Ubuntu', sans-serif;
}

button {
    width: 100px;
    align-self: end;
}

.imagesDiv {
    display: grid;
    grid-template-columns: 1fr 1fr;
}

.imagesDiv .image {
    height: 400px;
    margin-bottom: 1rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
}

.imagesDiv .image img {
    max-width: 500px;
    max-height: 350px;
}

.imagesDiv .fileInput {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.imagesDiv .fileInput label {
    cursor: pointer;
    border: 2px solid black;
    width: 500px;
    height: 280px;
    font-size: 2rem;
    font-family: 'Roboto Mono', monospace, sans-serif;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    font-weight: bold;
    border-radius: 10px;
    transition-duration: .2s;
}

.imagesDiv .fileInput label:active {
    width: 480px;
    height: 260px;
}

.imagesDiv label img {
    width: 50px;
}

.imagesDiv input[type="file"] {
    display: none;
}

#imgDeleteButton {
    width: 30px;
    height: 30px;
    cursor: pointer;
    transition-duration: .2s;
}

#imgDeleteButton:hover {
    width: 25px;
    height: 25px;
}

#imgDeleteButton:active {
    width: 20px;
    height: 20px;
}

#submitButton {
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

</style>
