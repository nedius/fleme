<template>
    <div>
        <section class="hero is-primary is-fullheight is-bold">
            <!-- Hero head: will stick at the top -->
            <div class="hero-head">
                <header class="navbar">
                    <div class="container">
                        <div class="navbar-brand">
                            <span class="navbar-item" style="background-color: transparent !important;">
                                <img src="/images/logo.png" alt="Logo">
                            </span>
                            <span class="navbar-burger burger" id="navbar-burger" @click="togleMenu">
                                <span></span>
                                <span></span>
                                <span></span>
                            </span>
                            
                        </div>

                        <div id="navbar-menu" class="navbar-menu">
                            <div class="navbar-end">
                                <a class="navbar-item is-active">
                                    Home
                                </a>
                                <router-link class="navbar-item" to="login">
                                    <font-awesome-icon class="icon" icon="sign-in-alt"/>
                                    <span>Login</span>
                                </router-link>
                            </div>
                        </div>
                    </div>
                </header>
            </div>
            <!-- Hero content: will be in the middle -->
            <div class="hero-body">
                <div class="container has-text-centered">
                    <h1 class="title">
                        A new way to chat with your co-workers.
                    </h1>
                    <h2 class="subtitle">
                        Fleme is the easiest way to communicate over <!-- <strike>voice, video, and</strike> --> text.
                    </h2>
                </div>
            </div>
            <!-- Hero footer: will stick at the bottom -->
            <!-- <div class="hero-foot">
                <nav class="tabs is-boxed is-fullwidth">
                    <div class="container">
                        <ul>
                            <li class="is-active"><a>button</a></li>
                            <li><a>button</a></li>
                            <li><a>button</a></li>
                            <li><a>button</a></li>
                            <li><a>button</a></li>
                            <li><a>button</a></li>
                        </ul>
                    </div>
                </nav>
            </div> -->
        </section>
        
    </div>
</template>

<script>
export default {
    name: 'Welcome',
    methods: {
        togleMenu(){
            let burger = document.getElementById('navbar-burger'),
                menu = document.getElementById('navbar-menu');
            burger.classList.toggle('is-active');
            menu.classList.toggle('is-active');
        },
    },
}
</script>

<style>
    .navbar-item{
        margin-left: 2px;
    }
</style>
