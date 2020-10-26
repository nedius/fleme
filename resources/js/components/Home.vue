<template>
  <div>
    <div id="app" class="columns is-gapless is-mobile">

        <!-- /* width: 90%; */
        position: absolute;
        /* left: -100px; */
        height: inherit;
        z-index: 31; -->
        <div class="column is-one-fifth has-background-grey-lightest fPanelWrapper">
            <div class="navbar has-background-grey-lighter" role="navigation" style="height: 52px;">
                <div class="navbar-brand">
                    <router-link class="" to="/">
                            <span class="navbar-item">
                            <img src="../../../public/img/logo.png" width="112" height="28">
                        </span>
                    </router-link>
                </div>
                <!-- <div id="navbarBasicExample" class="navbar-menu">
                    <div class="navbar-start">
                        <a class="navbar-item">
                            Channels
                        </a>

                        <a class="navbar-item">
                            Dm
                        </a>
                    </div>
                </div> -->
            </div>

            <!-- side bar channel -->
            <div class="fScroll fPanel fChMenu">

                <aside class="menu">
                    <div class="menu-label">
                        Channels 
                        <span class="is-pulled-right">
                            <font-awesome-icon v-if="hasPermissions('create-channel')" icon="plus" class="is-clickable" @click="createChannel"/>
                            <!-- <font-awesome-icon icon="cog" class="is-clickable" /> -->
                        </span>
                    </div>
                    <ul class="menu-list">
                        <!-- <li><a>Dashboard</a></li>
                        <li><a>Customers</a></li> -->
                        <li class="fChannel" v-for="channel in channelList" :key="channel.id">
                            <a v-if="!channel.deleted && channel.type != 'dm'" :class="`${currentChannel == channel.id ? ' boldThisChannel' : ''} `" style="display: block;" v-on:click="changeChannel(channel.id)">
                                {{ channel.name }}
                            </a>
                            <span class="fChannelIcon is-pulled-right">
                                <font-awesome-icon v-if="hasPermissions('edit-channel')" icon="cog" class="is-clickable" @click="editChannel(channel.id)"/>
                                <!-- @click="editChannel" -->
                            </span>
                        </li>
                    </ul>
                    <p class="menu-label">Direct Messages</p>
                    <ul class="menu-list">
                        <!-- <li><a>dm 1</a></li>
                        <li><a>dm 2</a></li> -->
                        <li class="fChannel" v-for="channel in channelList" :key="channel.id">
                            <a v-if="!channel.deleted && channel.type == 'dm'" :class="`${currentChannel == channel.id ? ' boldThisChannel' : ''} `" style="display: block;" v-on:click="changeChannel(channel.id)">
                                {{ getDmChannelName(channel) }}
                            </a>
                            <!-- <span class="fChannelIcon is-pulled-right">
                                <font-awesome-icon v-if="hasPermissions('edit-channel')" icon="cog" class="is-clickable" @click="editChannel(channel.id)"/>
                            </span> -->
                        </li>
                    </ul>
                </aside>
            </div>

            <div class="card has-background-grey-lighter" style="height: 52px;">
                <div class="card-content" style="padding: .75rem;">
                    <div class="media">
                        <div class="media-left">
                            <figure class="image is-32x32 is-rounded">
                                <img v-if="user.image" :src="`${user.image}`" alt="" style="border-radius: 290486px;" @click="openUser($event, user)">
                            </figure>
                        </div>
                        <div class="media-content" style="flex-basis: 60%;">
                            <div style="margin: 0; position: absolute; top: 50%; transform: translateY(-50%);">
                                <p class="title is-6"> {{ this.user.name }} </p>
                                <p class="subtitle is-7"> {{ this.user.name }} </p>
                            </div>
                        </div>
                        <div class="media-content">
                            <div style="margin: 0; position: absolute; top: 50%; transform: translateY(-50%);">
                                <font-awesome-icon icon="cog" class="is-clickable" @click="showMenu"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="column fPanelWrapper">
            <div class="navbar has-background-grey-lighter" role="navigation">
                <div class="navbar-menu" style="height: 52px;">
                    <div class="navbar-start">
                        <div v-if="hasPermissions('read-messages')" class="navbar-item">
                            # {{ channelName }}
                                
                            <!-- Channel name -->
                        </div>
                    </div>
                </div>
            </div>
            <div id="chatScroll" class="fScroll fPanel has-background-white-bis">

                <div v-for="msg in messages" :key="msg.id">
                    <!-- {{ item }} -->
                    <!-- <div style="white-space: pre-line; user-select: none;" v-html="item"></div> -->
                    <!-- <p>{{ data.id }}</p> -->
                    <message
                        v-bind:msg="msg"
                        v-bind:myMessage="msg.user.id == user.id"
                        v-bind:canEdit="hasPermissions('edit-messages')"
                        v-bind:canDelete="hasPermissions('delete-messages')"
                        v-bind:canEditOthers="hasPermissions('edit-others-messages')"
                        v-bind:canDeleteOthers="hasPermissions('delete-others-messages')"
                    ></message>

                </div>

            </div>
            <div id="inputNavbar" class="has-background-grey-white-biz">
                <!-- position: relative;
                bottom: 24px; -->
                <div class="peopleAreTyping">
                    <div style="background-color: white; border-radius: 100px; width: max-content;">
                        {{ typingText }}
                        <span v-if="typingText" class="dot">
                            <span>.</span>
                            <span>.</span>
                            <span>.</span>
                        </span>
                    </div>
                </div>

                <div v-if="hasPermissions('write-messages')" class="file" style="width: 50px;">
                    <label class="file-label">
                        <input class="file-input" type="file" name="resume" @change="sendMessageWithFile">
                        <span class="file-cta">
                            <span class="file-icon">
                                <font-awesome-icon icon="plus" class="is-clickable"/>
                            </span>
                        </span>
                    </label>
                </div>

                <textarea :disabled="!hasPermissions('write-messages')" id="textareaNavbar" class="input" type="text" role="textbox" :placeholder="`${ hasPermissions('write-messages') ? `Message #${channelName}` : 'You don\'t have permissions to write messages'}`" v-on:input="onInputMessageResize" v-on:keypress="onInputMessage" v-model.trim="chatInput"></textarea>
                <div v-if="hasPermissions('write-messages')" class="button" v-on:click="sendMessage">send</div>
            </div>
        </div>
        <div class="column is-one-fifth has-background-grey-lightest">
            <div class="navbar has-background-grey-lighter" role="navigation" style="height: 52px;">
                <!-- nav -->
                <div class="navbar-end">
                    <div class="navbar-item">
                        <!-- <router-link class="button is-link is-inverted is-outlined" to="logout">logout</router-link> -->
                        <div v-if="!hasPermissions('read-messages')" class="button" @click="showMenu">Settings</div>
                    </div>
                </div>
            </div>
            <div class="fScroll" style="height: calc(100vh - 52px);">
                <div v-for="user in allUsers" v-bind:key="user.id">
                    
                    <div v-if="!user.hide" class="card has-background-grey-lightest">
                        <div class="card-content" style="padding: .4rem;" @click="openUser($event, user)">
                            <div class="media">
                                <div class="media-left">
                                    <figure class="image is-32x32 is-rounded">
                                        <img :src="`${user.image}`" style="border-radius: 290486px;">
                                    </figure>
                                </div>
                                <div class="media-content" style="flex-basis: 60%;">
                                    <div style="margin: 0; position: absolute; top: 50%; transform: translateY(-50%);">
                                        <p class="subtitle is-6"> {{ user.name }} </p>
                                        <!-- <p class="subtitle is-7"> {{ this.user.name }} </p> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- side bar peoples -->
            <!-- <div class="button" @click="showModal">
                showModal
            </div> -->
        </div>
    </div>

    <modal
        v-if="isModalVisible"
        v-bind:type="modalType"
        v-bind:data="modalData"
        @close="closeModal"
    />
    <fleme-menu
        v-if="isMenuVisible"
        v-bind:user="user"
        @close="closeMenu"
        style="z-index: 30;"
    />
    <fleme-popup
        v-if="isPopupVisible"
        v-bind:type="popupType"
        v-bind:data="popupData"
        @close="closePopup"
    />

    <div v-if="!hasPermissions('read-messages')" class="noPermSpoiler has-background-grey-lighter">
        <h1 class="title" style="text-align: center; line-height: 25; height: 100%;">
            You don't have permissions to view this site
        </h1>
    </div>


    <!-- <div>Welcome home!</div> -->
    <!-- <router-link class="button is-success" to="/">Sing out</router-link> -->

    <!-- <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <h1>Dashboard</h1>

                <p>
                    <router-link :to="{ name: 'logout' }">Logout</router-link>
                </p>

                <div class="panel panel-default">
                    <div class="panel-heading">Dashboard</div>
                    <div class="panel-body">
                        <p>Data: "{{ data }}"</p>
                        <p>User: "{{ user }}"</p>
                    </div>
                </div>
                
                <div v-if="messages.length" class="panel panel-default">
                    <div class="panel-heading">Messages</div>
                    <div class="panel-body">
                        <message
                            v-for="message in messages"
                            v-bind:key="message.id"
                            v-bind:id="message.id"
                            v-bind:name="message.name"
                            v-bind:image="message.image"
                            v-bind:message="message.message"
                        ></message>
                    </div>
                </div>

            </div>
            <button v-on:click="getDashboard">Get dashboard</button>
        </div>
    </div> -->

  </div>
</template>

<script>

import message from './misc/message'
import modal from './misc/modal'
import flemeMenu from './misc/menu'
import flemePopup from './misc/popup'

export default {
    name: 'Home',
    components: {
        'message': message,
        'modal': modal,
        'fleme-menu': flemeMenu,
        'fleme-popup': flemePopup,
    },
    data() {
        return {
            data: '',
            user: {
                id: undefined,
                name: undefined,
                email: undefined,
                image: undefined,
                permissions: undefined,
                email_verified_at: undefined,
                created_at: undefined,
                updated_at: undefined,
                deleted_at: undefined,
            },
            allUsers: [],
            messages: [],
            // items: [],
            chatInput: '',
            currentChannel: 1,
            channelList: [],
            channelName: '',
            isModalVisible: false,
            modalType: '',
            modalData: {},
            isMenuVisible: false,
            typing: false,
            typingUsers: [],
            typingText: '',
            inputTimeout: undefined,
            isPopupVisible: false,
            popupType: '',
            popupData: {},
        }
    },
    mounted() {
        // for(let i=0;i<100;i++){
        //     this.items.push('chat aaaaaaaaaaaaaaaaa');
        // }

        // this.getDashboard();
        // axios.get('/dashboard', {
        //         headers: { Authorization: 'Bearer ' + localStorage.getItem('token') }}
        //     ).then(response => {
        //         // // console.log(response);
        //         // this.data = response.data.data;
        //     }).catch(error => {
        //     });

        if(this.$router.currentRoute.path == '/home/settings'){
            this.showMenu();
        }

        axios.get('/user', {
            headers: {
                Authorization: 'Bearer ' + localStorage.getItem('token')
            }
        }).then(response => {
            // console.log(response);
            this.user = response.data;
        }).catch(error => {
            if (error.response) {
                error.response.data;
                error.response.status;
                if(error.response.status == 401){
                    // localStorage.removeItem('token');
                    store.commit('logoutUser');
                    Echo.disconnect();

                    this.$router.push({ name: 'login' });
                }
            }
        });

        axios.get(`/users`, {
            headers: { 
                Authorization: 'Bearer ' + localStorage.getItem('token')
                }
            }
        ).then(response => {
            // console.log(response);
            this.allUsers = response.data
        }).catch(error => {
            console.error(error);
            // console.log(error.response);
        });

        setInterval(() => {
            axios.post('/auth/refresh', {}, { headers: { Authorization: 'Bearer ' + localStorage.getItem('token')}
            }).then(response => {
                localStorage.setItem('token', response.data.access_token);
                Echo.options.auth.headers.Authorization = `Bearer ${localStorage.getItem('token')}`;
            }).catch(error => {
                console.error(error);
                // console.log(error.response);
            });
        }, 5 * 60 * 1000 );

        this.loadChannel().then(response => {
            this.currentChannel = localStorage.getItem('lastChannel') ? localStorage.getItem('lastChannel') : 1;
            if(!this.channelList.find(channel => channel.id == this.currentChannel)){
                this.currentChannel = this.channelList[0].id;
                localStorage.setItem('lastChannel', this.currentChannel);
            }
            // console.log(this.findChannelById(this.channelList, this.currentChannel));
            let channel = this.findChannelById(this.channelList, this.currentChannel)
            if(channel.type == 'dm'){
                this.channelName = this.getDmChannelName(channel);
            }else{
                this.channelName = channel.name;
            }
            // this.changeChannel(this.currentChannel); 
            this.loadMessages().then(response => {
                this.changeShowedUsers();
            });
        });
        
        Echo.options.auth.headers.Authorization = `Bearer ${localStorage.getItem('token')}`;

        Echo.private(`client`)
            .listen('MessageSended', (e) => {
                // console.log(e);
                if(e.channel.id == this.currentChannel){
                    // console.log(e.message);
                    let fixedMessage = e.message;
                    fixedMessage.user = e.user;
                    fixedMessage.channel = e.channel;
                    fixedMessage.files = e.files;

                    this.messages.push(fixedMessage);
                    
                    // this.$nextTick(() => {
                        this.scrollDown();
                    // });
                }
            })
            .listen('MessageEdited', (e) =>{
                console.log(e);
                // let fixedMessage = e.message;
                // fixedMessage.user = e.user;
                let temp = this.messages.find(message => message.id == e.message.id);
                // console.log(temp);
                this.$set(temp, 'content', e.message.content);
                this.$set(temp, 'created_at', e.message.created_at);
                this.$set(temp, 'deleted_at', e.message.deleted_at);
                this.$set(temp, 'updated_at', e.message.updated_at);
                this.$set(temp, 'files', e.files);
                // console.log(temp);
                // temp.user = e.user;
                
                // this.channelList.push(e.channel);
            })
            .listen('MessageDeleted', (e) =>{
                // console.log(e.message);
                if(this.messages.find(message => message.id == e.message.id))
                    this.$set(this.messages.find(message => message.id == e.message.id), "deleted", true);
                // console.log(this.messages);
                // .push(e.channel);
                
            })
            .listen('ChannelAdded', (e) =>{
                // console.log(e.channel);
                // console.log(e);
                let channel = e.channel;
                channel.users = e.users;
                if(channel.users.find(user => user.id == this.user.id)){
                    this.channelList.push(channel);
                }
            })
            .listen('ChannelEdited', (e) =>{
                // console.log(e.channel);
                // console.log(e.channel.users.find(user => user.id == this.user.id));
                let thisUserExist = !!e.channel.users.find(user => user.id == this.user.id),
                    thisUserHaveThisChannel = !!this.channelList.find(channel => channel.id == e.channel.id && !channel.deleted),
                    hasThatChannel = !!this.channelList.find(channel => channel.id == e.channel.id);

                // console.log(thisUserExist, thisUserHaveThisChannel);

                if(thisUserExist && thisUserHaveThisChannel){
                    let temp = this.channelList.find(channel => channel.id == e.channel.id);
                    this.$set(temp, 'name', e.channel.name);
                    this.$set(temp, 'position', e.channel.position);
                    this.$set(temp, 'type', e.channel.type);
                    this.$set(temp, 'created_at', e.channel.created_at);
                    this.$set(temp, 'deleted_at', e.channel.deleted_at);
                    this.$set(temp, 'updated_at', e.channel.updated_at);
                    this.$set(temp, 'users', e.channel.users);
                }else if(!thisUserExist && thisUserHaveThisChannel){
                    this.$set(this.channelList.find(channel => channel.id == e.channel.id), "deleted", true);
                    this.$set(this.channelList.find(channel => channel.id == e.channel.id), 'users', e.channel.users);
                    let visibleChannel = this.channelList.find(channel => channel.deleted != true);
                    if(visibleChannel){
                        this.currentChannel = visibleChannel.id;
                        localStorage.setItem('lastChannel', this.currentChannel);
                    }
                }else if(thisUserExist && !thisUserHaveThisChannel){
                    if(this.channelList.find(channel => channel.id == e.channel.id && channel.deleted)){
                        this.$set(this.channelList.find(channel => channel.id == e.channel.id), "deleted", false);
                        this.$set(this.channelList.find(channel => channel.id == e.channel.id), 'users', e.channel.users);
                    }else{
                        this.channelList.push(e.channel);
                        this.$set(this.channelList.find(channel => channel.id == e.channel.id), 'users', e.channel.users);
                    }
                }else if(!thisUserExist && !thisUserHaveThisChannel){
                    if(hasThatChannel){
                        let temp = this.channelList.find(channel => channel.id == e.channel.id);
                        this.$set(temp, 'name', e.channel.name);
                        this.$set(temp, 'position', e.channel.position);
                        this.$set(temp, 'type', e.channel.type);
                        this.$set(temp, 'created_at', e.channel.created_at);
                        this.$set(temp, 'deleted_at', e.channel.deleted_at);
                        this.$set(temp, 'updated_at', e.channel.updated_at);
                        this.$set(temp, 'users', e.channel.users);
                    }
                }

                if(hasThatChannel){
                    let temp = this.channelList.find(channel => channel.id == e.channel.id);
                    this.$set(temp, 'name', e.channel.name);
                    this.$set(temp, 'position', e.channel.position);
                    this.$set(temp, 'type', e.channel.type);
                    this.$set(temp, 'created_at', e.channel.created_at);
                    this.$set(temp, 'deleted_at', e.channel.deleted_at);
                    this.$set(temp, 'updated_at', e.channel.updated_at);
                    this.$set(temp, 'users', e.channel.users);
                }

                this.channelList.sort((a, b) => {
                    return a.position - b.position;
                });

                e.channel.users.forEach(user => {
                    if(!this.allUsers.find(allUser => allUser.id == user.id)){
                        this.allUsers.push(user);
                        this.allUsers.sort((a, b) => {
                            return a.id - b.id;
                        });
                    }
                });

                // this.allUsers.forEach(user => {
                //     this.$set(user, "hide", !e.channel.users.find(userFromCh => userFromCh.id == user.id));
                // });

                this.changeShowedUsers();
                
                // if(e.channel.users.find(user => user.id == this.user.id)){
                //     let temp = this.channelList.find(channel => channel.id == e.channel.id);
                //     this.$set(temp, 'name', e.channel.name);
                //     this.$set(temp, 'created_at', e.channel.created_at);
                //     this.$set(temp, 'deleted_at', e.channel.deleted_at);
                //     this.$set(temp, 'updated_at', e.channel.updated_at);
                // }else{
                //     if(this.channelList.find(channel => channel.id == e.channel.id))
                //         this.$set(this.channelList.find(channel => channel.id == e.channel.id), "deleted", true);
                // }
            })
            .listen('ChannelDeleted', (e) =>{
                // console.log(e.channel);
                // this.channelList.push(e.channel);
                this.$set(this.channelList.find(channel => channel.id == e.channel.id), "deleted", true);
                this.$set(this.channelList.find(channel => channel.id == e.channel.id), "deleted_at", e.channel.deleted_at);
            })
            .listen('UserChanged', (e) => {
                // console.log(e);
                if(e.user.id == this.user.id){
                        
                    let permissions = [];

                    e.user.permissions.forEach(perm => {
                        permissions.push(perm);
                    });
                    e.user.roles.forEach(role => {
                        role.permissions.forEach(perm => {
                            if(!permissions.find(p => p.id == perm.id)){
                                permissions.push(perm);
                            }
                        })
                    });

                    // this.user.permissions = permissions;
                    this.$set(this.user, 'permissions', permissions);
                    this.$set(this.user, 'id', e.user.id);
                    this.$set(this.user, 'name', e.user.name);
                    this.$set(this.user, 'email', e.user.email);
                    this.$set(this.user, 'image', e.user.image);
                    this.$set(this.user, 'email_verified_at', e.user.email_verified_at);
                    this.$set(this.user, 'created_at', e.user.created_at);
                    this.$set(this.user, 'updated_at', e.user.updated_at);

                    // console.log(permissions);
                }
            })
            .listenForWhisper('typing', (e) => {
                let obj = {
                    id: e.id,
                    name: e.name,
                    typing: e.typing,
                    channelId: e.channelId,
                }
                // console.log(obj, this.typingUsers);

                if(obj.typing){
                    this.typingUsers.push(obj);
                }
                else{
                    this.typingUsers = this.typingUsers.filter(user => user.id != obj.id);
                }
                // console.log(this.typingUsers);
                this.prepareTypingText();
            });

    },
    beforeDestroy() {
        // Echo.leave('client');
        Echo.disconnect();
    },
    methods:{
        // getDashboard(){
        //     axios.get('/dashboard', {
        //         headers: { Authorization: 'Bearer ' + localStorage.getItem('token') }}
        //     ).then(response => {
        //         // console.log(response);
        //         this.data = response.data.data;
        //     }).catch(error => {
        //         // if (error.response) {
        //         //   error.response.data;
        //         //   error.response.status;
        //         //   if(error.response.status == 401){
        //         //     this.$router.push({ name: 'Welcome' })
        //         //   }
        //         // }
        //     });
        // },

        prepareTypingText() {
            let names = [];
            this.typingUsers.forEach(user => {
                if(user.typing && user.channelId == this.currentChannel)
                    names.push(user.name);
            });
            if(names.length < 1){
                this.typingText = '';
            }else if(names.length == 1){
                this.typingText = `${names[0]} is typing `;
            }else if(names.length == 2){
                this.typingText = `${names[0]} and ${names[1]} are typing `;
            }else if(names.length > 2){
                this.typingText = `Several people are typing `;
            }

            // this.typingText = names.join(', ');
            // console.log(this.typingText, names.length);
        },

        sendMessage(){
            this.stopTyping();
            
            if(!(isEmpty(this.chatInput.trim()) || isBlank(this.chatInput.trim()) || this.chatInput.trim().isEmpty())){ 

                axios.post(`message/${this.currentChannel}`, 
                    {content: this.chatInput.trim()},
                    {headers: { Authorization: 'Bearer ' + localStorage.getItem('token')}}
                ).then(response => {
                    // console.log(response)
                }).catch(error => {
                    console.error(error);
                    console.log(error.response);
                });

                this.chatInput = '';
                // this.scrollDown();
            }
        },

        onInputMessageResize(e){
            e.target.style.height = "5px";
            e.target.style.height = (e.target.scrollHeight + 2)+"px";

            if(e.target.scrollHeight + 2 > 208)
                e.target.style.overflowY = "scroll";
            else
                e.target.style.overflowY = "hidden";

            if((isEmpty(this.chatInput.trim()) || isBlank(this.chatInput.trim()) || this.chatInput.trim().isEmpty())){
                this.stopTyping();
            }
        },

        startTyping(){
            if(!this.typing){
            this.typing = true;
            Echo.private('client')
                .whisper('typing', {
                    id: this.user.id,
                    name: this.user.name,
                    typing: this.typing,
                    channelId: this.currentChannel,
                });
            }
        },
        stopTyping(){
            if(this.typing){
                this.typing = false;
                Echo.private('client')
                    .whisper('typing', {
                        id: this.user.id,
                        name: this.user.name,
                        typing: this.typing,
                        channelId: this.currentChannel,
                    });
            }
        },

        onInputMessage(e){
            // e.preventDefault();
            // clog(e);
            // clog(e.key);
            // clog(this.chatInput);
            // debugger;

            if(!this.typing){
                this.startTyping();

                this.inputTimeout = setTimeout(() => {
                    this.stopTyping();
                }, 10000);
            }else if(this.typing){
                clearTimeout(this.inputTimeout);

                this.inputTimeout = setTimeout(() => {
                    this.stopTyping();
                }, 10000);

            }

            if(e.key === "Enter"){
                if(!e.shiftKey){
                    e.preventDefault();
                    if(!(isEmpty(this.chatInput.trim()) || isBlank(this.chatInput.trim()) || this.chatInput.trim().isEmpty())){  
                        // this.items.push(this.chatInput.trim());

                        // clog(this.chatInput);
                        // console.log(this.chatInput);
                        // sendMessage(this.chatInput.trim());
                        this.sendMessage();

                        this.chatInput = '';
                    }
                }
                // else if(e.shiftKey){
                //     // this.chatInput += '\n';
                // }
            }
            // else{
            //     this.chatInput += e.key;
            // }
        },

        scrollDown(force = false){
            let panel = document.getElementById(`chatScroll`);
            // console.log(`(${panel.scrollHeight} - ${panel.scrollTop}) ${panel.scrollHeight - panel.scrollTop} == ${panel.offsetHeight} (${panel.scrollHeight - panel.scrollTop == panel.offsetHeight}, force ${force})`);
            if( panel.scrollHeight - panel.scrollTop == panel.offsetHeight || force){
                this.$nextTick(() => {
                    panel.scrollTop = panel.scrollHeight;
                });
            }
        },

        loadChannel(){
            return axios.get(`/channel`, {
                    headers: { 
                        Authorization: 'Bearer ' + localStorage.getItem('token')
                        }
                    }
                ).then(response => {
                    // console.log(response);
                    this.channelList = response.data 
                    this.channelList.sort((a, b) => {
                        return a.position - b.position;
                    });
                }).catch(error => {
                    console.error(error);
                    // console.log(error.response);
                });
        },

        loadMessages(){            
            return axios.get(`/message/${this.currentChannel}`, {
                    headers: { 
                        Authorization: 'Bearer ' + localStorage.getItem('token')
                        }
                    }
                ).then(response => {
                    let messages = response.data;
                    // console.log(messages);
                    // for(let i=response.data.length;i>0;i--){
                    //     this.items.push();
                    // }
                    
                    this.messages = [];

                    for(let i = messages.length - 1; i >= 0; i--){
                        // console.log(messages[i]);
                        let msg = messages[i];
                        // console.log(msg);
                        this.messages.push(msg);
                        
                        
                        // this.$nextTick(() => {
                            // this.scrollDown();
                        // });
                    }
                        
                    // this.$nextTick(() => {
                        this.scrollDown(true);
                    // });

                }).catch(error => {
                    console.error(error);
                    // console.log(error.response);
                });
        },

        changeChannel(e){
            // console.log(e);
            if(this.currentChannel == e){
                return;
            }
            this.currentChannel = e;
            localStorage.setItem('lastChannel', this.currentChannel);
            this.prepareTypingText();
            this.loadMessages().then(response => {
                let channel = this.findChannelById(this.channelList, this.currentChannel);
                // console.log(channel);
                if(channel.type == 'dm'){
                    // console.log('dm', this.getDmChannelName(channel));
                    this.channelName = this.getDmChannelName(channel);
                }else{
                    // console.log('not dm');
                    this.channelName = channel.name;
                }
                this.changeShowedUsers();
            });
            
            // axios.get(`/channel/${this.currentChannel}`, {
            //     headers: { 
            //         Authorization: 'Bearer ' + localStorage.getItem('token')
            //         }
            //     }
            // ).then(response => {
            //     // console.log(response);
            //     // this.channelList = response.data
            //     this.channelName = response.data.name;
            // }).catch(error => {
            //     console.error(error);
            //     // console.log(error.response);
            // });
        },
        changeShowedUsers(){
            this.allUsers.forEach(user => {
                    this.$set(user, 'hide', true);
                let userId = user.id,
                    newChannel = this.channelList.filter(channelUser => channelUser.id == this.currentChannel)[0];
                // console.log(this.channelList.filter(channelUser => channelUser.id == this.currentChannel)[0]);
                // console.log(newChannel.users.filter(channelUser => channelUser.id == userId));
                if(newChannel.users.filter(channelUser => channelUser.id == userId).length > 0){
                    this.$set(user, 'hide', false);
                }
            });
        },
        findChannelById(channels, id){
            return channels.find(channel => channel.id == id);
            
            // channels.forEach(channel => {
            //     console.log(channel)
            //     if(channel.id == id){
            //         return channel;
            //     }

            // });
        },
        showModal() {
            this.isModalVisible = true;
        },
        closeModal() {
            this.isModalVisible = false;
        },
        createChannel() {
            this.modalType = "createChannel";
            this.modalData = {
                title: "Create new Channel",
            };
            this.showModal();
        },
        editChannel(channelId) {
            this.modalType = "editChannel";
            this.modalData = {
                title: `Edit Channel`,
                selectedChannel: channelId,
                canDelete: this.hasPermissions('delete-channel'),
            };
            this.showModal();
        },
        editSelfUser() {
            this.modalType = "editSelfUser";
            this.modalData = {
                title: `Edit your profile`,
                user: this.user,
            };
            this.showModal();
        },
        saveUserData(data) {
            console.log(data)
        },
        hasPermissions(permission){
            let can = false;
            try{
                this.user.permissions.forEach(perm => {
                    if(perm.slug == permission){
                        can = true;
                    }
                });
            }catch(e){
                // 
            }
            // console.log(permission, can);
            return can;
        },
        showMenu() {
            // console.log(window.location.pathname+window.location.search);
            window.history.replaceState(null, '', `${ window.location.pathname+window.location.search == '/home' ? 'home/' : ''}settings`);
            this.isMenuVisible = true;
        },
        closeMenu() {
            window.history.replaceState(null, '', '../home');
            this.isMenuVisible = false;
        },
        openUser(e, user){
            // console.log(e, user);
            this.popupType = "userProfile";
            this.popupData = {
                user: user,
                event: e,
            };
            if(this.isPopupVisible){
                setTimeout(() => {
                    this.showPopup();
                }, 100);
            }
            this.showPopup();
        },
        showPopup() {
            this.isPopupVisible = true;
        },
        closePopup() {
            this.isPopupVisible = false;
        },
        openDmChannel(user){
            let arr = [this.user.id, user.id],
                name = arr.join('-');
            arr.reverse();
            let nameR = arr.join('-'),
                channel = this.channelList.find(channel => channel.name == name || channel.name == nameR);

            if(channel){
                // console.log(name, nameR, channelId);
                this.changeChannel(channel.id);
            }else{
                axios.post(`/dm`, {
                        with: user.id,
                    }, {
                        headers: { 
                            Authorization: 'Bearer ' + localStorage.getItem('token')
                        }
                    }
                ).then(response => {
                    // console.log(response);
                    this.changeChannel(response.data.id);
                }).catch(error => {
                    console.error(error);
                    // console.log(error.response);
                });
                // console.log(name, nameR, channelId);
                // console.log('creating dm');
            }
        },
        getDmChannelName(channel){
            let name = channel.name,
                users = [];
            try{
                channel.users.forEach(user => {
                    if(user.id != this.user.id){
                        users.push(user.name);
                    }
                });
                name = users.join(', ');
            }catch(e){
                //
            }
            return name;
        },

        sendMessageWithFile(e){
            // console.log(e.target.files);

            if(e.target.files.length == 1){
                this.modalType = "sendMessageWithFile";
                this.modalData = {
                    title: `Send`,
                    save: `Send`,
                    file: e.target.files[0],
                };
                this.showModal();
                e.target.value = '';
            }            
        },
    }
}
</script>

<style scoped>
    #app {
        height: 100vh;
    }
    #inputNavbar{
        /* bottom: 0; */
        padding-left: 10px;
        padding-right: 10px;
        padding-bottom: 10px;
        display: flex;
    }
    .fPanelWrapper{
        display: flex;
        flex-direction: column;
    }
    .fPanel{
        flex-grow: 1;
    }
    .fScroll{
        overflow-y: auto;
    }
    #textareaNavbar{
        resize: none;
        overflow-x: hidden;
        overflow-y: hidden;
        min-height: 38px;
        max-height: 208px;
    }
    .boldThisChannel{
        font-weight: bold;
    }
    .fChMenu{
        padding: 10px;
    }
    .is-clickable {
        cursor: pointer;
    }
    .fChannel:hover .fChannelIcon{
        visibility: visible;
    }
    .fChannelIcon{
        visibility: hidden;
        transform: translateY(-27px);
    }
    .navbar{
        z-index: 0 !important;
    }
    .navbar-menu{
        display: flex;
        flex-grow: 1;
        flex-shrink: 0;

        background-color: inherit;
        box-shadow: inherit;
    }
    #textareaNavbar:disabled::placeholder {
        color: #4a4a4a;
        text-align: center;
    }
    .peopleAreTyping{
        position: relative;
        width: 0;
        height: 0;
        right: -10px;
        bottom: 24px;
    }

    .dot span:nth-child(1) {
        opacity: 0;
        -webkit-animation: dot 1.3s infinite;
        -webkit-animation-delay: 0.0s;
        animation: dot 1.3s infinite;
        animation-delay: 0.0s;
    }
    .dot span:nth-child(2) {
        opacity: 0;
        -webkit-animation: dot 1.3s infinite;
        -webkit-animation-delay: 0.2s;
        animation: dot 1.3s infinite;
        animation-delay: 0.2s;
    }
    .dot span:nth-child(3) {
        opacity: 0;
        -webkit-animation: dot 1.3s infinite;
        -webkit-animation-delay: 0.3s;
        animation: dot 1.3s infinite;
        animation-delay: 0.3s;
    }
    @-webkit-keyframes dot {
        0% { opacity: 0; }
        50% { opacity: 0; }
        100% { opacity: 1; }
    }
    @keyframes dot {
        0% { opacity: 0; }
        50% { opacity: 0; }
        100% { opacity: 1; }
    }

    .noPermSpoiler{
        position: fixed;
        top: 52px;
        left: 0;
        right: 0;
        bottom: 0;
        height: calc(100vh - 52px);
    }
</style>