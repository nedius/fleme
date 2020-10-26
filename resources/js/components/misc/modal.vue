<template>
    <transition name="fade" appear>
        <div id="modalRoot">
            <div class="modal">
                <div class="modal-background" @click="closeModal"></div>
                    <div class="modal-card">
                    <header class="modal-card-head">
                        <p class="modal-card-title" style="max-width: 580px; word-break: break-all;">
                            {{this.data.title}}
                        </p>
                        <button class="delete" aria-label="close" @click="closeModal"></button>
                    </header>
                    <section class="modal-card-body">
                        <!-- Content ... -->

                        <div v-if="type == 'createChannel' || type == 'editChannel'">
                            <div class="field">
                                <label class="label">Channel name</label>
                                <div class="control">
                                    <input :class="`input ${error.name ? `is-danger` : ''}`" type="text" placeholder="Channel name" v-model="newChannelName">
                                </div>
                                <p v-if="error.name" class="help is-danger">{{ error.name}}</p>
                            </div>

                            <!-- <div class="field">
                                <label class="label">Users</label>
                                <div class="dropdown">
                                    <div class="dropdown-trigger">
                                        <button class="button" aria-haspopup="true" aria-controls="dropdown-menu">
                                            <span>Choose Users</span>
                                            <font-awesome-icon icon="angle-down" size="xs"/>
                                        </button>
                                    </div>

                                </div>
                            </div> -->

                            <nav class="panel">
                                <p class="panel-heading">
                                    Choose Users
                                </p>
                                <div class="panel-block">
                                    <p class="control has-icons-left">
                                        <font-awesome-icon class="icon is-left is-size-7" style="height: 1.5rem !important; width: 1.5rem !important; transform: translate(30%, 30%);" icon="search"/>
                                        <input class="input" type="text" placeholder="Search" v-model="searchUsers" @input="searchUserInput">
                                    </p>
                                </div>
                                <div style="height: 400px; overflow-y: auto;">
                                    <div v-bind:key="user.id" v-for="user in allUsers">
                                        <label v-if="!user.hide" class="panel-block">
                                            <input type="checkbox" v-model="user.checked">
                                            {{ user.name }}
                                        </label>
                                    </div>

                                    <!-- <label class="panel-block">
                                        <input type="checkbox">
                                        admin
                                    </label>
                                    <label class="panel-block">
                                        <input type="checkbox">
                                        user
                                    </label> -->
                                </div>
                                <div class="panel-block">
                                    <button class="button is-link is-outlined is-fullwidth" @click="toggleAllUsers">
                                        Toggle all
                                    </button>
                                </div>
                            </nav>

                            <div v-if="type == 'editChannel'" class="field is-pulled-right">
                                <div class="control">
                                    <div :class="`button is-danger${data.canDelete ? '' : ' is-disabled is-outlined'}`" @click="deleteChannel" >Delete channel</div>
                                </div>
                            </div>

                        </div>

                        <div v-if="type == 'editSelfUser'">

                            <label class="label">Change email</label>
                            <div class="field has-addons">
                                <div class="control is-expanded">
                                    <input id="changeEmailInput" :class="`input ${error.email ? `is-danger` : ''}`" type="email" :placeholder="`${this.data.user.email}`" v-model="newUserEmail" @input="onEmailInput">
                                </div>
                                <div class="control">
                                    <div id="changeEmailButton" class="button" @click="saveEmail" disabled>Change Email</div>
                                </div>
                            </div>
                            <p v-if="error.email" class="help is-danger">{{ error.email }}</p>

                            <hr>

                            <label class="label">Change password</label>
                            <div class="field has-addons">
                                <div class="control is-expanded">
                                    <input id="changePasswordInput" :class="`input ${error.password ? `is-danger` : ''}`" type="password" placeholder="New passowrd" v-model="newUserPassword" @input="onPasswordInput">
                                </div>
                                <div class="control">
                                    <div id="changePasswordButton" class="button" @click="savePassword" disabled>Change Passowrd</div>
                                </div>
                            </div>
                            <p v-if="error.password" class="help is-danger">{{ error.password }}</p>


                        </div>

                        
                        <div v-if="type == 'sendMessageWithFile'">
                            <div style="width:100%; height: 80px;">
                                <font-awesome-icon v-if="showSave" :icon="fileType" style="font-size: 5em;"/>
                                <font-awesome-layers v-if="!showSave" style="font-size: 5em;">
                                    <font-awesome-icon icon="file" />
                                    <font-awesome-icon icon="plus" style="color: white; transform: translateY(10px) rotateZ(45deg);" transform="shrink-6"/>
                                </font-awesome-layers>
                            </div>
                            <div style="margin-top: 10px;">
                                <textarea v-if="showSave" id="textareaInput" class="input" type="text" role="textbox" placeholder="Text (optional)" v-on:input="$parent.onInputMessageResize" v-on:keypress="onInputMessage" v-on:keyup.esc="closeModal" v-model.trim="textInput"></textarea>
                                <p v-if="!showSave" class="is-size-4 has-text-danger">{{ saveCancelReason }}</p>
                            </div>
                            <!-- <div>
                                <div>name: {{ this.file.name }}</div>
                                <div>size: {{ this.file.size }}</div> -->
                                <!-- <div>type: {{ this.file.type }}</div> -->
                                <!-- <div>lastModified: {{ this.file.lastModified }}</div>
                                <div>lastModifiedDate: {{ this.file.lastModifiedDate }}</div>
                                <div>webkitRelativePath: {{ this.file.webkitRelativePath }}</div>
                            </div> -->
                        </div>

                    </section>
                    <footer class="modal-card-foot">
                        <button v-if="showSave" class="button is-success" @click="saveModal">{{ saveString }}</button>
                        <button v-if="showCancel" class="button" @click="closeModal">{{ cancelString }}</button>
                    </footer>
                </div>
            </div>


            <!-- <div>
                <slot name="header"></slot>
                <slot name="body"></slot>
                <slot name="footer"></slot>
            </div> -->
            <!-- <div id="modalBackground" @click="closeModal"></div> -->
        </div>
    </transition>
</template>

<script>
export default {
    name: 'modal',
    props: [
        'data',
        'type',
    ],
    data() {
        return {
            error: {},
            selectedUsers: [],
            allUsers: [],
            currentChannel: {},
            newChannelName: '',
            searchUsers: '',
            allUserToggle: false,
            inputTimeout: undefined,
            newUserEmail: '',
            newUserPassword: '',

            showSave: true,
            showCancel: true,
            saveString: 'Save changes',
            cancelString: 'Cancel',

            file: {},
            fileType: 'file',
            textInput: '',
            saveCancelReason: '',
        }
    },
    mounted() {
        // console.log(this.type, this.data);

        let hasUsersDownloaded;
        if(this.type == `createChannel` || this.type == `editChannel`){
            hasUsersDownloaded = axios.get(`/users`, {
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
        }
        if(this.type == `editChannel`){
            hasUsersDownloaded.then(() => {
                axios.get(`/channel/${this.data.selectedChannel}`, {
                    headers: { 
                        Authorization: 'Bearer ' + localStorage.getItem('token')
                        }
                    }
                ).then(response => {
                    // console.log(response);
                    this.currentChannel = response.data;
                    this.newChannelName = this.currentChannel.name;
                    this.currentChannel.users.forEach(user => {
                        this.$set(this.allUsers.find(allUser => allUser.id == user.id), 'checked', true);
                    });
                }).catch(error => {
                    console.error(error);
                    // console.log(error.response);
                });
            });
        }

        if(this.type == 'editSelfUser'){
            this.newUserEmail = this.data.user.email;
            this.showSave = false;
        }

        if(this.type == 'sendMessageWithFile'){
            if(!this.$parent.hasPermissions('write-messages')){
                this.closeModal();
            }
            if(this.data.file){
                this.file = this.data.file;
                this.data.title += ` ${this.file.name}`;
                
                if(this.file.type.includes('image')){
                    this.fileType = 'file-image';
                }else if(this.file.type.includes('audio')){
                    this.fileType = 'file-audio';
                }else if(this.file.type.includes('video')){
                    this.fileType = 'file-video';
                }else{
                    this.fileType = 'file';
                }

                // console.log(this.file);
                if(this.file.size / 1024 > 8192){
                    this.showSave = false;
                    this.saveCancelReason = 'The file may not be greater than 8192 kilobytes.';
                    this.data.title = 'Error';
                    // console.log('The file may not be greater than 8192 kilobytes.');
                }
            }else{
                this.closeModal();
            }

            this.$nextTick(() => {
                let el = document.getElementById('textareaInput');
                if(el){
                    el.focus();
                }
            });
        }

        if(this.data.save){
            this.saveString = this.data.save;
        }
        if(this.data.cancel){
            this.cancelString = this.data.cancel;
        }
    },
    methods: {
        closeModal(){
            this.$emit('close');
        },
        saveModal(){
            if(this.type == `createChannel`) this.createChannel();
            if(this.type == `editChannel`) this.editChannel();
            if(this.type == `sendMessageWithFile`) this.sendMessage();
        },
        createChannel(){
            let userSelected = [];
            let isSelectedAll = true;
            this.allUsers.forEach(user => {
                if(user.checked)
                    userSelected.push(user.id);
                else
                    isSelectedAll = false;
            });
            this.error = {};
        
            if(this.newChannelName.trim() !== ''){
                axios.post(`/channel`, {
                        name: this.newChannelName.trim(),
                        isSelectedAll: isSelectedAll,
                        users: userSelected,
                    }, {
                        headers: { 
                            Authorization: 'Bearer ' + localStorage.getItem('token')
                        }
                    }
                ).then(response => {
                    // console.log(response);
                    this.closeModal();
                }).catch(error => {
                    console.error(error);
                    // console.log(error.response);
                    if(error.response.data.errors.name.join('\n')) 
                        this.$set(this.error, 'name', error.response.data.errors.name.join('\n'));
                });

                // console.log(`save channel`);
                // console.log(`channel name:`, this.newChannelName);
                // console.log(`selected users:`, userSelected);
                // console.log(`isSelectedAll:`, isSelectedAll);
            }else{
                this.$set(this.error, 'name', 'You need to specify name.'); 
            }
        },
        searchUserInput(){
            clearTimeout(this.inputTimeout);

            // this.inputTimeout

            this.inputTimeout = setTimeout(() => {
                console.time("search");
                this.allUsers.filter(user => 
                    this.$set(user, 'hide', !user.name.toLowerCase().includes(this.searchUsers.toLowerCase()))
                    // user.hide = !user.name.toLowerCase().includes(this.searchUsers.toLowerCase())
                );
                console.timeEnd("search");
                this.allUserToggle = false;
            }, 500);
        },
        toggleAllUsers(){
            // console.log('toggled', this.allUserToggle);
            this.allUserToggle = !this.allUserToggle;
            this.allUsers.forEach(user => {
                if(!user.hide){
                    this.$set(user, 'checked', this.allUserToggle);
                    // user.checked = this.allUserToggle;
                    // console.log(user);
                }
            });
        },
        editChannel(){
            let userSelected = [];
            let isSelectedAll = true;
            this.allUsers.forEach(user => {
                if(user.checked)
                    userSelected.push(user.id);
                else
                    isSelectedAll = false;
            });
            this.error = {};
        
            if(this.newChannelName.trim() !== ''){
                axios.put(`/channel/${this.data.selectedChannel}`, {
                        name: this.newChannelName.trim(),
                        isSelectedAll: isSelectedAll,
                        users: userSelected,
                    }, {
                        headers: { 
                            Authorization: 'Bearer ' + localStorage.getItem('token')
                        }
                    }
                ).then(response => {
                    // console.log(response);
                    this.closeModal();
                }).catch(error => {
                    console.error(error);
                    console.log();
                    if(error.response.data.errors.name.join('\n')) 
                        this.$set(this.error, 'name', error.response.data.errors.name.join('\n'));
                });
            }else{
                this.$set(this.error, 'name', 'You need to specify name.'); 
            }
        },
        deleteChannel(){
            // console.log('delete channel');
            if(this.data.canDelete){
                axios.delete(`/channel/${this.data.selectedChannel}`, {
                            headers: { 
                                Authorization: 'Bearer ' + localStorage.getItem('token')
                            }
                        }
                    ).then(response => {
                        // console.log(response);
                        this.closeModal();
                    }).catch(error => {
                        console.error(error);
                        // console.log(error.response);
                    });
            }
        },

        onEmailInput(){
            let button = document.getElementById('changeEmailButton'),
                input = document.getElementById('changeEmailInput');
            input.classList.remove('is-success');

            if(this.data.user.email != this.newUserEmail && this.validateEmail(this.newUserEmail)){
                button.removeAttribute('disabled');
                button.classList.add('is-success');
                this.error.email = undefined;
            }else{
                button.setAttribute('disabled', true);
                button.classList.remove('is-success');
                if(!this.validateEmail(this.newUserEmail)){
                    this.error.email = `You need to specify correct email.`;
                }
            }
            this.$forceUpdate();
        },
        saveEmail(){
            let button = document.getElementById('changeEmailButton'),
                input = document.getElementById('changeEmailInput');

            if(!button.hasAttribute('disabled'))
                axios.put(`/user/email`, {
                            email: this.newUserEmail.trim(),
                        }, {
                            headers: { 
                                Authorization: 'Bearer ' + localStorage.getItem('token')
                            }
                        }
                    ).then(response => {
                        // console.log(response);
                        this.$set(this.$parent.user, 'email', response.data.email);
                        this.error.email = undefined;
                        input.classList.add('is-success');
                        button.setAttribute('disabled', true);
                        this.$forceUpdate();
                        // this.closeModal();
                    }).catch(error => {
                        console.error(error);
                        this.$set(this.error, 'email', error.response.data.errors.email.join("\n"));
                        this.$forceUpdate();
                        // console.log(this.error.email);
                    });
        },
        validateEmail(email){
            let regex = /(?:[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*|"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])$/;
            return regex.test(email);
        },
        validatePassword(password){
            let regex = /^[\w*\d*]{6,32}$/;
            return regex.test(password);
        },
        onPasswordInput(){
            let button = document.getElementById('changePasswordButton'),
                input = document.getElementById('changePasswordInput');
            input.classList.remove('is-success');

            if(this.validatePassword(this.newUserPassword)){
                button.removeAttribute('disabled');
                button.classList.add('is-success');
                this.error.password = undefined;
            }else{
                button.setAttribute('disabled', true);
                button.classList.remove('is-success');
                if(!this.validateEmail(this.newUserPassword)){
                    this.error.password = 'Your password needs to be more then 6 and less then 32 character.';
                }
            }
            this.$forceUpdate();  
        },
        savePassword(){
            let button = document.getElementById('changePasswordButton'),
                input = document.getElementById('changePasswordInput');

            if(!button.hasAttribute('disabled'))
                axios.put(`/user/password`, {
                            password: this.newUserPassword.trim(),
                        }, {
                            headers: { 
                                Authorization: 'Bearer ' + localStorage.getItem('token')
                            }
                        }
                    ).then(response => {
                        // console.log(response);
                        // this.$set(this.$parent.user, 'email', response.data.email);
                        this.error.password = undefined;
                        input.classList.add('is-success');
                        button.setAttribute('disabled', true);
                        this.newUserPassword = '';
                        this.$forceUpdate();
                        // this.closeModal();
                    }).catch(error => {
                        console.error(error);
                        this.$set(this.error, 'password', error.response.data.errors.password.join("\n"));
                        this.$forceUpdate();
                        // console.log(this.error.email);
                    });
        },

        onInputMessage(e){
            // e.preventDefault();
            // console.log(e);

            if(e.key === "Enter"){
                if(!e.shiftKey){
                    e.preventDefault();
                    this.sendMessage();

                    this.textInput = '';
                }
            }
        },

        sendMessage(){
            let formData = new FormData();
            formData.append('content', this.textInput);
            formData.append('file', this.file);

            axios.post(`message/${this.$parent.currentChannel}/file`, formData,
                {
                    headers: { 
                        Authorization: 'Bearer ' + localStorage.getItem('token'),
                        'Content-Type': 'multipart/form-data',
                    }
                }
            ).then(response => {
                // console.log(response);
                this.textInput = '';
                this.closeModal();
            }).catch(error => {
                console.error(error);
                // console.log(error.response);
            });
        },
    },
}
</script>

<style scoped>
    /* .modal-background{
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.1);
        z-index: 30;
    } */

    .modal {
        /* background: #FFFFFF; */
        /* box-shadow: 2px 2px 20px 1px;
        overflow-x: auto; */
        display: flex;
        /* flex-direction: column; */
        /* opacity: 1;
        transform: translateY(0px); */
    }

    .is-disabled{
        background-color: transparent !important;
        border-color: #f1466880 !important;
        box-shadow: none !important;
        color: #f1466880 !important;
        cursor: default;
    }

    /* .fade-enter-active {
        transition: all .3s ease;
    }
    .fade-leave-active {
        transition: all .8s cubic-bezier(1.0, 0.5, 0.8, 1.0);
    }
    .fade-enter, .fade-leave-to {
        transform: translateX(10px);
        opacity: 0; 
    }*/
    .fade-enter-active {
        transition: all .1s ease;
    }
    .fade-leave-active {
        transition: all .1s cubic-bezier(1.0, 0.5, 0.8, 1.0);
    }
    .fade-enter, .fade-leave-to {
        /* transform: translateY(-10px); */
        opacity: 0;
    }

    
    #textareaInput{
        resize: none;
        overflow-x: hidden;
        overflow-y: hidden;
        min-height: 38px;
        max-height: 208px;
    }
    
</style>>