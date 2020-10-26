<template>
    <!-- <div> -->
        <section class="hero is-primary is-fullheight is-bold">
            <!-- <div>Email</div>
            <input type="email" name="" v-model="email">
            <div>Password</div> 
            <input type="password" name="" v-model="password">
            <button class="button is-success" @click="login">login</button> -->
            <!-- <router-link class="button is-success" to="home">Login</router-link> -->

            <header class="navbar">
                <div class="container">
                    <div class="navbar-brand">
                        <router-link class="" to="/">
                            <span class="navbar-item" style="background-color: transparent !important;">
                                <img src="/images/logo.png" alt="Logo">
                            </span>
                        </router-link>
                    </div>
                </div>
            </header>           

            <div class="card">
                <div class="card-content">
                    <p class="title has-text-black">
                        Please Login
                    </p>

                    <form v-on:submit.prevent="submitLogin">

                        <b class="has-text-danger" v-if="error != ''">{{ error }}</b>

                        <div class="field">
                            <label class="label" for="inputEmail">Email address</label>
                            <div class="control">
                                <input class="input" type="email" id="inputEmail" placeholder="Email address" required autofocus v-model="email">
                            </div>
                        </div>

                        <div class="field">
                            <label class="label" for="inputPassword" >Password</label>
                            <div class="control">
                                <input class="input" type="password" id="inputPassword" placeholder="Password" required v-model="password">
                            </div>
                        </div>    

                        <div class="field is-grouped is-pulled-right" style="margin-bottom: 30px;">
                            <div class="control">
                                <button class="button is-success" type="submit">Login</button>
                            </div>
                        </div>                

                        <!-- <button class="button is-success" type="submit">Sign in</button> -->
                    </form>

                    <router-link class="is-link is-success" :to="{ name: 'register' }">Don't have account? Register new right now!</router-link>
                    <!-- <div class="is-link" @click="fastEnter">fast enter</div> -->

                    <!-- <button class="button is-success" @click="me">me</button> -->

                </div>
            </div>

        </section>
    <!-- </div> -->
</template>

<script>
import store from '../store'

export default {
    name: "Auth",
    // components: {
    // },

    data() {
        return {
            email: "",
            password: "",
            access_token: "",
            error: "",
        }
    },

    methods:{
        submitLogin() {
            axios.post('/auth/login', {
                email: this.email,
                password: this.password
            }).then(response => {
                store.commit('loginUser');
                localStorage.setItem('token', response.data.access_token);
                Echo.options.auth.headers.Authorization = `Bearer ${localStorage.getItem('token')}`;
                // this.$router.push({ name: 'home' });
                window.location.href = './home';
            }).catch(error => {
                if(error.response.data.description)
                    this.error = error.response.data.description;
                console.error(error);
                console.log(error.response);
            });
        },
        me(){
            axios.post("/auth/me").then(
                response => {
                    console.log(response);
                }
            ).catch(
                error => {
                    console.error(error);
                }
            )
        },
        fastEnter(){
            this.email = "admin@admin.com";
            this.password = "123456";
        }
    }
};
</script>

<style scoped>
    .card{
        position: absolute;
        /* max-width: 40%; */
        width: 350px;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        border-radius: 5px;
    }
</style>