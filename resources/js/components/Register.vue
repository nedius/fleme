<template>
    <div>

        <section class="hero is-primary is-fullheight is-bold">

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
                        Registation form
                    </p>

                    <b class="has-text-danger" v-if="errorTitle">{{ errorTitle }}</b>

                    <form v-on:submit.prevent="submitRegister">

                        <div class="field">
                            <label class="label" for="inputEmail">Email address</label>
                            <div class="control">
                                <input :class="`input${errors.email ? ' is-danger' : ''}`" type="email" id="inputEmail" placeholder="Email address" required autofocus v-model="email">
                            </div>
                            <span v-if="errors.email">
                                <p class="help is-danger">
                                    {{ errors.email }}
                                </p>
                            </span>
                        </div>

                        <div class="field">
                            <label class="label" for="inputName">Name</label>
                            <div class="control">
                                <input :class="`input${errors.name ? ' is-danger' : ''}`"  type="text" id="inputName" placeholder="Name" required v-model="name">
                            </div>
                            <span v-if="errors.name">
                                <p class="help is-danger">
                                    {{ errors.name }}
                                </p>
                            </span>
                        </div>

                        <div class="field">
                            <label class="label" for="inputPassword">Password</label>
                            <div class="control">
                                <input :class="`input${errors.password ? ' is-danger' : ''}`"  type="password" id="inputPassword" placeholder="Password" required v-model="password">
                            </div>
                            <span v-if="errors.password">
                                <p class="help is-danger">
                                    {{ errors.password }}
                                </p>
                            </span>
                        </div>

                        <div class="field">
                            <div class="control">
                                <label class="checkbox">
                                <input type="checkbox" id="inputCheckbox" name="inputCheckbox" required v-model="checkbox">
                                    I agree to the <a href="#">terms and conditions</a>
                                </label>
                            </div>
                        </div>

                        <div class="field is-grouped is-pulled-right">
                            <div class="control">
                                <button class="button is-success" type="submit">Register</button>
                            </div>
                        </div>                

                        <!-- <button class="button is-success" type="submit">Sign in</button> -->
                        <span style="color: transparent;">just for padding</span>
                    </form>

                    <!-- <button class="button is-success" @click="me">me</button>
                    <button class="button is-success" @click="fastEnter">fast enter</button> -->

                </div>
            </div>

        </section>

        <!-- <form v-on:submit.prevent="submitRegister">
            <h1>Please register new account</h1> -->

            <!-- <p v-if="errors.length">
                <b>There are this problems:</b>
                <ul>
                    <li :key="error.id" v-for="error in errors">{{ error }}</li>
                </ul>
            </p> -->

            <!-- <div v-if="errorTitle">
                <b>{{ errorTitle }}</b>
            </div>
            
            <div v-if="errors">
                <div v-if="errors.email">
                    <p :key="error.id" v-for="error in errors.email">{{ error }}</p>
                </div>
            </div>
            <label for="inputEmail" >Email address</label>
            <input type="email" id="inputEmail" placeholder="Email address" required autofocus v-model="email">
            
            <div v-if="errors">
                <div v-if="errors.name">
                    <p :key="error.id" v-for="error in errors.name">{{ error }}</p>
                </div>
            </div>
            <label for="inputName" >Name</label>
            <input type="text" id="inputName" placeholder="Name" required autofocus v-model="name">

            <div v-if="errors">
                <div v-if="errors.password">
                    <p :key="error.id" v-for="error in errors.password">{{ error }}</p>
                </div>
            </div>
            <label for="inputPassword" >Password</label>
            <input type="password" id="inputPassword" placeholder="Password" required v-model="password">

            <label for="inputCheckbox" >ARE YOU SHURE ABOUT THAT ??</label>
            <input type="checkbox" id="inputCheckbox" name="inputCheckbox" required v-model="checkbox">

            <button class="button is-success" type="submit">Sign up</button>
        </form> -->
    </div>
</template>

<script>
import store from '../store'

export default {
    data() {
        return {
            errors: [],
            errorTitle: undefined,
            email: "",
            name: "",
            password: "",
            checkbox: false,
        }
    },
    mounted: function (){
        if(store.state.isLoggedIn){
            this.$router.push({ name: 'home'});
        }
    },
    methods:{
        submitRegister() {
            this.errors = {};

            if (!this.name) {
                this.errors.name = 'You need to specify your name.';
            }
            // else if(!(this.name.length >= 1 && this.name.length <= 32)){
            //     this.errors.name = 'Your name can\'t be smaller then 1 or greater then 32 characters.';
            // }
            if (!this.email) {
                this.errors.email = 'You need to specify your email.';
            }else if (!this.validateEmail(this.email)) {
                this.errors.email = 'You need to specify correct email.';
            }
            if (!this.password) {
                this.errors.password = 'You need to specify your password.';
            }
            // else if(!(this.password.length >= 6 && this.password.length <= 32)){
            //     this.errors.password = 'Your password can\'t be smaller then 1 or greater then 32 characters.';
            // }
            if (!this.checkbox) {
                this.errors.checkbox = 'You need to agree to Term of Service.';
            }

            if (this.errors === {}) {
                return;
            }

            if(this.checkbox){
                axios.post('/register', {
                    email: this.email,
                    name: this.name,
                    password: this.password
                }
                ).then(response => {
                    localStorage.setItem('token', response.data.access_token);
                    store.commit('loginUser');
                    this.$router.push({ name: 'home' });
                }).catch(error => {
                    console.error(error);
                    // error.response;
                    console.log(error.response);
                    this.errorTitle =  error.response.data.message;
                    this.errors = {};
                    if(error.response.data.errors.email) this.errors.email = error.response.data.errors.email.join('\n');
                    if(error.response.data.errors.name) this.errors.name = error.response.data.errors.name.join('\n');
                    if(error.response.data.errors.password) this.errors.password = error.response.data.errors.password.join('\n');
                    // this.errors = error.response.data.errors;
                    // this.errors = error.response.data.errors;
                    // this.errors = error.response.data.errors;
                    // console.log(this.errors, error.response.data);
                    // this.errors.push('There was problem with registration.');
                });
            }
        },
        validateEmail(email){
            let regex = /(?:[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*|"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])$/;
            return regex.test(email);
        }
    }
}
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