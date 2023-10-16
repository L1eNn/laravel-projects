import {createRouter, createWebHistory} from 'vue-router'
import {useAuthStore} from "./stores/AuthStore.js";
import {usePostsStore} from "./stores/PostsStore.js";

const routes = [
    {
        path: '/',
        component: ()=> import('./components/posts/IndexComponent.vue'),
        name: 'posts.index'
    },
    {
        path: '/post/create',
        component: () => import('./components/posts/CreateComponent.vue'),
        name: 'post.create'
    },
    {
        path: '/posts/edit',
        component: () => import('./components/posts/EditComponent.vue'),
        name: 'post.edit'
    },
    {
        path: '/posts/show',
        component: () =>import('./components/posts/ShowComponent.vue'),
        name: 'posts.show'
    },
    {
        path: '/home',
        name: 'home'
    },
    {
        path: '/login',
        component: ()=> import('./components/auth/LoginComponent.vue'),
        name: 'login'
    },
    {
        path: '/register',
        component: ()=> import('./components/auth/RegisterComponent.vue'),
        name: 'register'
    },
    {
        path: '/profile',
        component: () => import('./components/profile/IndexComponent.vue'),
        name: 'profile.index'
    },
    {
        path: '/profile/create',
        component: () => import('./components/profile/CreateComponent.vue'),
        name: 'profile.create'
    },
]

 const router = new createRouter({
    history: createWebHistory(),
    routes
})

router.beforeEach(async (to, from, next) => {
    const authStore = useAuthStore()
    const postsStore = usePostsStore()



    if (!authStore.authenticated) {
        postsStore.removePostIdToEdit()
        if (to.name === 'login' || to.name === 'register' || to.name === 'posts.index') {
            return next()
        } else {
            return next({
                name: 'login'
            })
        }
    } else {
        if (to.name === 'home') {
            postsStore.removePostIdToEdit()
            return next({name: 'posts.index'})
        } else if (to.name === 'login' || to.name === 'register') {
            postsStore.removePostIdToEdit()
            return next({
                name: 'profile.index'
            })
        } else if (to.name === 'profile.index' && !authStore.profileStatus) {
            postsStore.removePostIdToEdit()
            return next({
                name: 'profile.create'
            })
        } else if (to.name === 'profile.create' && authStore.profileStatus) {
            postsStore.removePostIdToEdit()
            return next({
                name: 'profile.index'
            })
        } else if (to.name === 'posts.show') {
            let post = await postsStore.postShow()
            if (!post) {
                return next({
                    name: 'posts.index'
                })
            } else {
                return next()
            }
        } else if (to.name === 'post.edit'){
            let post = await postsStore.getCertainPost()
            if (!post) {
                return next({
                    name: 'post.create'
                })
            } else {
                return next()
            }
        } else {
            postsStore.removePostIdToEdit()
            return next()
        }
    }
})

export default router



