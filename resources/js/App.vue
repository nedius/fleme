<template>
    <router-view id="routerView" :key="$router.fullPath" :style="`--height: ${computedWidth}`" v-on:resize="resizePage" />
</template>

<script>

import store from './store'

export default {
    name: 'App',

    data() {
        return {
            computedWidth: '',
        }
    },
    
    beforeMount() {

        if(localStorage.token) {
            axios.get('/user', {
                headers: {
                    Authorization: 'Bearer ' + localStorage.getItem('token')
                }
            },
            ).then(response => {
                store.commit('loginUser')
            }).catch(error => {
                if (error.response.status === 401 || error.response.status === 403) {
                    store.commit('logoutUser')
                    localStorage.setItem('token', '')
                    this.$router.push({name: 'login'})
                }

            });
        }

        // window.addEventListener("resize", this.resizePage);
        // console.log(document.body.clientHeight);

    },
    methods:{
        resizePage(){
            // console.log('resize', window, window.screen.height);
            this.computedWidth = `${window.screen.height}px`;
        }
    },
    
}
</script>

<style>
    /* html, body, #app */
    #routerView{
        --height: 100vh;
        height: var(--height);

        /* height: 100%; */
        width: 100vw;
    }
    html, body{
        overflow: hidden;

        scrollbar-color: hsla(204, 48%, 29%, 25%) transparent;
        scrollbar-width: thin;
    }
    *{
        -webkit-touch-callout: none;
          -webkit-user-select: none;
           -khtml-user-select: none;
             -moz-user-select: none;
              -ms-user-select: none;
                  user-select: none;

        /* user-drag: none; */
        -moz-user-select: none;
        -webkit-user-drag: none;
    }
    [disabled].pagination-previous, [disabled].pagination-next, [disabled].pagination-link, [disabled].pagination-ellipsis, [disabled].file-cta, [disabled].file-name, .select select[disabled], [disabled].textarea, [disabled].input, [disabled].button, fieldset[disabled] .pagination-previous, fieldset[disabled] .pagination-next, fieldset[disabled] .pagination-link, fieldset[disabled] .pagination-ellipsis, fieldset[disabled] .file-cta, fieldset[disabled] .file-name, fieldset[disabled] .select select, .select fieldset[disabled] select, fieldset[disabled] .textarea, fieldset[disabled] .input, fieldset[disabled] .button {
        cursor: default !important;
    }
    .has-background-grey-lightest{
        background-color: hsl(204, 48%, 93%);
    }

    /* width */
    ::-webkit-scrollbar {
        width: 5px;
        height: 5px;
    }

    /* Track */
    ::-webkit-scrollbar-track {
        background: transparent; 
    }
    
    /* Handle */
    ::-webkit-scrollbar-thumb {
        background: hsla(204, 48%, 29%, 25%); 
        border-radius: 100px;
    }

    /* Handle on hover */
    ::-webkit-scrollbar-thumb:hover {
        background: hsla(204, 48%, 21%, 30%); 
    }
</style>