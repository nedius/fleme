<template>
    <transition name="fade" appear>
        <div class="menuRoot columns is-gapless is-mobile">
            <div class="column is-one-quarter has-background-light fPanelWrapper">
                <div class="fScroll fPanel fOptionsPadding">
                    <aside class="menu is-pulled-right fSettingsSideMenu fMenuScroll">
                        <div class="menu-label">
                            Menu
                            <!-- <span class="is-pulled-right">
                                <font-awesome-icon v-if="hasPermissions('create-channel')" icon="plus" class="is-clickable" @click="createChannel"/>
                                <font-awesome-icon icon="cog" class="is-clickable" />
                            </span> -->
                        </div>
                        <ul class="menu-list">
                            <div v-for="channel in channelList" :key="channel.id">
                                <li v-if="channel.type == undefined && channel.action == undefined">
                                    <a :class="`${currentChannel == channel.id ? 'is-active' : ''} ${channel.class ? channel.class : ''} has-text-black`" v-on:click="changeChannel(channel)">
                                        {{ channel.name }}
                                    </a>
                                </li>
                                <hr v-if="channel.type == 'divider'" class="rounded">
                                <router-link v-if="channel.action == 'logout'" class="has-text-danger" to="logout">{{ channel.name }}</router-link>
                            </div>
                        </ul>
                    </aside>
                </div>
            </div>

            <div class="column fPanelWrapper has-background-white">
                <div class="fScroll fullHeight">
                    <div class="fOptionsPadding">
                        <!-- vnutrenosti menu
                        <input type="text" name="" id="forColor" @keyup.enter="getColorFromName">
                        <div class="button" @click="getColorFromName">getColor</div> -->

                        <div v-if="currentChannel == 1">
                            <form>

                                <label class="label">Change avatar</label>
                                <div style="display: flex;">
                                    <div class="file has-name is-boxed">
                                        <label class="file-label" style="width: 256px; height: 256px;">
                                            <input class="file-input" type="file" name="resume" @change="handleFileChange">
                                            <span class="file-cta" :style="`height: 256px; border-radius: 100%; padding: 6em;`">
                                                <img class="file-image" :src="`${user.image}`" style="position: absolute; top: 0; width: 256px; height: 256px; border-radius: 100%;" alt="">
                                                <div class="file-bg"/>
                                                <font-awesome-icon icon="upload" class="file-icon"/>
                                                <span class="file-label" style="">
                                                    Choose a file…
                                                </span>
                                            </span>
                                        </label>
                                    </div>
                                    
                                    <div class="button" @click="resetAvatar">Reset</div>
                                    <p v-if="error.file" class="help is-danger" style="font-size: 1.2rem; padding-left: 15px;">{{ error.file }}</p>
                                </div>

                                <hr>

                                <label class="label">Change email</label>
                                <div class="field has-addons">
                                    <div class="control is-expanded">
                                        <input id="changeEmailInput" :class="`input ${error.email ? `is-danger` : ''}`" type="email" :placeholder="`${this.user.email}`" v-model="newUserEmail" @input="onEmailInput" autocomplete="email">
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
                                        <input id="changePasswordInput" :class="`input ${error.password ? `is-danger` : ''}`" type="password" placeholder="New passowrd" v-model="newUserPassword" @input="onPasswordInput" autocomplete="new-password">
                                    </div>
                                    <div class="control">
                                        <div id="changePasswordButton" class="button" @click="savePassword" disabled>Change Passowrd</div>
                                    </div>
                                </div>
                                <p v-if="error.password" class="help is-danger">{{ error.password }}</p>
                            </form>
                        </div>

                        <div v-if="currentChannel == 3">
                            <!-- roles -->
                            <div style="display: flex;">
                                <aside class="menu fSettingsSideMenu fMenuScroll" style="width: 290px;">
                                    <div class="menu-label">
                                        Roles
                                    </div>
                                    <ul class="menu-list">
                                        <div v-for="role in roleList" :key="role.id">
                                            <li class="fRole">
                                                <a v-if="!editingRole || role.id != currentEditingRoleId" :class="`${currentRole == role.id ? 'is-active' : ''} has-text-black`" v-on:click="changeRole(role)">
                                                    {{ role.name }}
                                                </a>
                                                <span v-if="!editingRole || role.id != currentEditingRoleId" class="fRoleIcon is-pulled-right">
                                                    <font-awesome-icon v-if="hasPermissions('edit-roles')" icon="pencil-alt" class="is-clickable" @click="initEditingRole(role.id)"/>
                                                </span>
                                                <div style="margin-left: 10px;" v-if="editingRole && role.id == currentEditingRoleId">
                                                    <input class="input" ref="roleInput" type="text" v-model.trim="roleName" @keydown.esc="deInitEditingRole" @keydown.enter="editRole">
                                                    <span class="has-text-link link" @click="deInitEditingRole">cancel</span> or <span class="has-text-link link" @click="editRole">save</span>
                                                </div>
                                            </li>
                                        </div>
                                        <li>
                                            <a v-if="!creatingNewRole" @click="initCreateRole">
                                                Create Role

                                            </a>
                                            <div style="margin-left: 10px;" v-if="creatingNewRole">
                                                <input class="input" ref="newRoleInput" type="text" v-model.trim="newRoleName" @keydown.esc="deInitCreateRole" @keydown.enter="createRole">
                                                <span class="has-text-link link" @click="deInitCreateRole">cancel</span> or <span class="has-text-link link" @click="createRole">save</span>
                                                <!-- v-model.trim="editString" @input="handleEdit" @change="handleEdit" @keypress="onInputMessage" @keydown.esc="editing = !editing;" -->
                                            </div>
                                        </li>
                                    </ul>
                                </aside>

                                <div style="margin-top: 20px; margin-left: 300px; position: fixed;">
                                    <div v-for="perm in permissionList" :key="perm.id" style="margin-bottom: 5px;">
                                        <input type="checkbox" :id="`${perm.name}-${perm.id}`" v-model="perm.checked" @change="checkRolesPermInput">
                                        <label :for="`${perm.name}-${perm.id}`"> {{ perm.name }} </label>
                                    </div>

                                    <div style="margin-top: 20px; margin-left: 220px; top: 40px; position: fixed; width: 144px;">
                                        <div :class="`button`" style="display: block;" @click="resetRole" >Clear Role</div>
                                        <div :class="`button is-danger`" style="display: block; margin-top: 5px;" @click="deleteRole" >Delete Role</div>
                                    </div>

                                    <transition name="fade-in" appear v-if="showRoleSaveDialog">
                                        <div>
                                            <div class="button is-success" @click="savePermissions">Save</div>
                                            <div class="button" @click="resetPermissions">Reset</div>
                                        </div>
                                    </transition>
                                </div>
                            </div>

                        </div>

                        <div v-if="currentChannel == 4">
                            <!-- users -->

                            <div style="display: flex;">
                                <aside class="menu fSettingsSideMenu fMenuScroll" style="width: 290px;">
                                    <div class="menu-label">
                                        Users
                                    </div>
                                    <ul class="menu-list">
                                        <div v-for="user in userList" :key="user.id">
                                            <li class="fRole">
                                                <a :class="`${currentUser == user.id ? 'is-active' : ''} has-text-black`" v-on:click="changeUser(user)">
                                                    {{ user.name }}
                                                </a>
                                            </li>
                                        </div>
                                    </ul>
                                </aside>

                                <div style="margin-top: 20px; margin-left: 300px; position: fixed;">
                                    <p class="menu-label">Permissions</p>
                                    <div v-for="perm in permissionList" :key="`perm-${perm.id}`" style="margin-bottom: 5px;">
                                        <input type="checkbox" :id="`${perm.name}-${perm.id}`" v-model="perm.checked" @change="checkUserRolesInput">
                                        <label :for="`${perm.name}-${perm.id}`"> {{ perm.name }} </label>
                                    </div>

                                    <p class="menu-label">Roles</p>
                                    <div v-for="role in roleList" :key="`role-${role.id}`" style="margin-bottom: 5px;">
                                        <input type="checkbox" :id="`${role.name}-${role.id}`" v-model="role.checked" @change="checkUserRolesInput">
                                        <label :for="`${role.name}-${role.id}`"> {{ role.name }} </label>
                                    </div>

                                    <div style="margin-top: 20px; margin-left: 220px; top: 40px; position: fixed; width: 144px;">
                                        <div :class="`button`" style="display: block;" @click="clearUserRoles" >Clear All</div>
                                        <transition name="fade-in" appear v-if="showUserRoleSaveDialog">
                                            <div style="margin-top: 10px;">
                                                <div class="button is-success" @click="saveUserRoles">Save</div>
                                                <div class="button" @click="resetUserRoles">Reset</div>
                                            </div>
                                        </transition>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div v-if="currentChannel == 5">
                            <!-- channels -->

                            <!-- chList -->
                            <div class="fScroll fPanel fChMenu">
                                <aside class="menu">
                                    <div class="menu-label">
                                        Channels
                                        <span class="is-pulled-right">
                                            <font-awesome-icon v-if="$parent.hasPermissions('create-channel')" icon="plus" class="is-clickable" @click="$parent.createChannel"/>
                                            <!-- <font-awesome-icon icon="cog" class="is-clickable" /> -->
                                        </span>
                                    </div>
                                    <ul class="menu-list">
                                        <!-- <li><a>Dashboard</a></li>
                                        <li><a>Customers</a></li> -->
                                        <li class="fRole" v-for="channel in chList" :key="channel.id">
                                            <a v-if="channel.type != 'dm'" :class="`${currentChannel == channel.id ? 'boldThisChannel' : ''} ${channel.deleted_at ? 'has-text-danger' : ''} `" style="display: block;">
                                                <span>{{ channel.name }}</span><span style="padding-left: 25px;"> <font-awesome-icon icon="users" /> {{ channel.users ? channel.users.length : '0' }}</span>
                                            </a>
                                            <span v-if="channel.type != 'dm'" class="fRoleIcon is-pulled-right" style="visibility: visible;">
                                                <font-awesome-icon v-if="$parent.hasPermissions('edit-channel') && !channel.deleted_at" icon="cog" class="is-clickable" @click="$parent.editChannel(channel.id)"/>
                                                <font-awesome-icon v-if="$parent.hasPermissions('edit-channel') && channel.deleted_at" icon="trash-restore-alt" class="is-clickable has-text-danger" @click="restoreChannel(channel.id)"/>
                                                <!-- @click="editChannel" -->
                                            </span>
                                        </li>
                                    </ul>
                                </aside>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="column is-one-fifth has-background-white">
                <div class="fOptionsPadding">
                    <font-awesome-icon class="is-clickable" icon="times" size="lg" @click="closeModal"/>
                    <!-- <router-link class="button has-text-danger" to="logout">logout</router-link> -->
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
export default {
    name: 'menu-fleme',
    props: {
        'user': Object,
    },
    data() {
        return {
            currentChannel: 1,
            channelList: [],

            error: [],

            // for my account
            newUserEmail: '',
            newUserPassword: '',

            //for roles
            currentRole: 0,
            roleList: [],
            showRoleSaveDialog: false,
            creatingNewRole: false,
            newRoleName: '',
            editingRole: false,
            currentEditingRoleId: 0,
            roleName: '',

            //for permissions
            permissionList: [],

            //for channels
            chList: [],

            //for users
            userList: [],
            currentUser: 0,
            showUserRoleSaveDialog: false,
            userOriginalValues: {},
        }
    },
    mounted() {
        // console.log(this.type, this.data);

        if(this.permissionList.length == 0 && this.hasPermissions('edit-roles')){
            this.loadPermissions();
        }
        this.permissionChanged();

        // console.log(this.channelList);
    },
    watch: {
        user: function () {
            if(this.permissionList.length == 0 && this.hasPermissions('edit-roles')){
                this.loadPermissions();
            }
            this.permissionChanged();
        }
    },
    methods: {
        closeModal(){
            this.$emit('close');
        },
        loadPermissions(){
            axios.get(`/permissions`, {
                        headers: {
                            Authorization: 'Bearer ' + localStorage.getItem('token')
                        }
                    }
                ).then(response => {
                    // console.log(response);
                    this.permissionList = response.data;
                    this.permissionList.forEach( perm => {
                        this.$set(perm, 'checked', false);
                    });
                }).catch(error => {
                    console.error(error);
                    // console.log(error.response);
                });
        },
        permissionChanged(){
            this.channelList = [];

            this.channelList.push({ id: 1, name: `My account`});
            if(this.hasPermissions('edit-roles') || this.hasPermissions('edit-channel')) {
                this.channelList.push({ id: 2, type: `divider`});
            }
            if(this.hasPermissions('edit-roles')) {
                this.channelList.push({ id: 3, name: `Roles`});
                this.channelList.push({ id: 4, name: `Users`});
            }
            if(this.hasPermissions('edit-channel')) {
                this.channelList.push({ id: 5, name: `Channels`});
            }
            this.channelList.push({ id: 6, type: `divider`});
            this.channelList.push({ id: 7, name: `Logout`, class: 'has-text-danger', action: 'logout'});
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
        changeChannel(e){
            if(e.id == 3){
                this.showRoleSaveDialog = false;
                this.creatingNewRole = false;
                this.newRoleName = '';
                this.editingRole = false;
                this.currentEditingRoleId = 0;
                this.currentRole = 0;
                this.roleName = '';
                this.loadRoles().then(response => {
                    if(this.roleList[0]){
                        this.changeRole(this.roleList[0]);
                        // this.currentRole = this.roleList[0].id;
                    }
                    this.currentChannel = e.id;
                })
            }else if(e.id == 4){
                this.currentUser = 0;
                this.loadRoles().then(response => {
                    axios.get(`/users`, {
                            headers: {
                                Authorization: 'Bearer ' + localStorage.getItem('token')
                                }
                            }
                        ).then(response => {
                            // console.log(response);
                            this.userList = response.data
                            if(this.userList[0]){
                                this.changeUser(this.userList[0]);
                                // this.currentRole = this.roleList[0].id;
                            }
                            this.currentChannel = e.id;
                        }).catch(error => {
                            console.error(error);
                            // console.log(error.response);
                        });
                })
            }else if(e.id == 5){
                this.loadChannels().then(r => {
                    this.chList = this.$parent.channelList;
                    this.currentChannel = e.id;
                });
            }else{
                this.currentChannel = e.id;
            }

            if(e.id != 4){
                this.userOriginalValues = {};
            }
        },
        loadRoles(){
            return axios.get(`/roles`, {
                        headers: {
                            Authorization: 'Bearer ' + localStorage.getItem('token')
                        }
                    }
                ).then(response => {
                    // console.log(response);
                    this.roleList = response.data;
                }).catch(error => {
                    console.error(error);
                    // console.log(error.response);
                });
        },
        changeRole(e){
            // this.permissionList.forEach(perm => perm.checked = false);
            // this.roleList.forEach(role => role.checked = false);
            const change = function(vm){
                vm.currentRole = e.id;
                vm.permissionList.forEach(perm => {
                    if(vm.roleList.find(role => role.id == vm.currentRole).permissions.find(p => p.id == perm.id)){
                        perm.checked = true;
                    }else{
                        perm.checked = false;
                    }
                });
                vm.deInitCreateRole();
                vm.deInitEditingRole();
            };
            if(this.currentRole == e.id){
                return;
            }
            if(this.currentRole == 0){
                change(this);
            }
            let currentRole = this.roleList.find(role => role.id == this.currentRole);
            if(!currentRole){
                change(this);
                return;
            }
            // console.log(currentRole.permissions);
            if(this.isDiferent(currentRole.permissions, this.permissionList)){
                // console.log('is diferent');
            }else{
                change(this);
            }
        },
        isDiferent(a, b){
            let isDiferent = false;
            let bCount = 0;

            b.forEach( perm => {
                if(perm.checked){
                    bCount++;
                }
            });

            if(a.length != bCount){
                return true;
            }

            for(let i = 0; i < b.length; i++){
                if(!b[i].checked != !a.find(perm => perm.id == b[i].id)){
                    isDiferent = true;
                    break;
                }
            }
            // a.forEach(a => {
            //     if(b[]){
            //         isDiferent = true;
            //     }
            // });

            return isDiferent;
        },
        checkRolesPermInput(){
            this.showRoleSaveDialog = this.isDiferent(this.roleList.find(role => role.id == this.currentRole).permissions, this.permissionList);
            // console.log(this.isDiferent(this.roleList.find(role => role.id == this.currentRole).permissions, this.permissionList));
        },
        savePermissions(){
            let selectedPermissions = [],
                roleId = this.currentRole;
            this.permissionList.forEach(perm => {
                if(perm.checked){
                    selectedPermissions.push(perm.id);
                }
            });

            axios.post(`/role/${roleId}`, {
                        permissions: selectedPermissions,
                    }, {
                        headers: {
                            Authorization: 'Bearer ' + localStorage.getItem('token')
                        }
                    }
                ).then(response => {
                    // console.log(response);
                    this.showRoleSaveDialog = false;
                    this.$set(this.roleList.find(role => role.id == roleId), 'permissions', response.data.permissions);
                    this.resetPermissions();
                    // this.loadRoles().then(response => {
                    //     this.resetPermissions();
                    // });
                }).catch(error => {
                    console.error(error);
                    // console.log(error.response);
                });
        },
        resetPermissions(){
            this.permissionList.forEach(perm => {
                if(this.roleList.find(role => role.id == this.currentRole).permissions.find(p => p.id == perm.id)){
                    perm.checked = true;
                }else{
                    perm.checked = false;
                }
            });
            this.showRoleSaveDialog = false;
        },
        initCreateRole(){
            this.creatingNewRole = true;
            this.$nextTick(() => {
                // console.log(this.$refs.newRoleInput);
                this.$refs.newRoleInput.focus();
            });
        },
        deInitCreateRole(){
            this.newRoleName = '';
            this.creatingNewRole = false;
        },
        createRole(){
            // console.log('createChannel');
            if(this.newRoleName.length == 0){
                return;
            }

            axios.post(`/role`, {
                        name: this.newRoleName,
                    }, {
                        headers: {
                            Authorization: 'Bearer ' + localStorage.getItem('token')
                        }
                    }
                ).then(response => {
                    // console.log(response);
                    this.deInitCreateRole();
                    // this.$set(this.roleList.find(role => role.id == roleId), 'permissions', response.data.permissions);
                    // this.roleList.push(response.data);
                    // this.resetPermissions();
                    this.loadRoles().then(responsee => {
                        this.resetPermissions();
                        this.changeRole({id: response.data.id});
                    });
                }).catch(error => {
                    console.error(error);
                    // console.log(error.response);
                });
        },

        initEditingRole(id){
            this.editingRole = true;
            this.currentEditingRoleId = id;
            this.roleName = this.roleList.find(role => role.id == id).name;
            this.changeRole({id: id});
            this.$nextTick(() => {
                // console.log(this.$refs.roleInput);
                this.$refs.roleInput[0].focus();
            });
        },
        deInitEditingRole(){
            this.roleName = '';
            this.currentEditingRoleId = 0;
            this.editingRole = false;
        },
        editRole(){
            // console.log('createChannel');
            if(this.roleName == this.roleList.find(role => role.id == this.currentEditingRoleId).name){
                this.deInitEditingRole();
            }
            if(this.roleName.length == 0){
                return;
            }

            axios.post(`/role/${this.currentEditingRoleId}`, {
                        name: this.roleName,
                    }, {
                        headers: {
                            Authorization: 'Bearer ' + localStorage.getItem('token')
                        }
                    }
                ).then(response => {
                    // console.log(response);
                    this.loadRoles().then(r => {
                        this.deInitEditingRole();
                    });
                }).catch(error => {
                    console.error(error);
                    // console.log(error.response);
                });
        },

        resetRole(){
            this.permissionList.forEach(perm => {
                perm.checked = false;
            });
            this.checkRolesPermInput();
        },
        deleteRole(){
            axios.delete(`/role/${this.currentRole}`, {
                            headers: {
                                Authorization: 'Bearer ' + localStorage.getItem('token')
                            }
                        }
                    ).then(response => {
                        // console.log(response);
                        this.loadRoles();
                    }).catch(error => {
                        console.error(error);
                        // console.log(error.response);
                    });
        },

        // getColorFromName(){
        //     let input = document.getElementById('forColor').value,
        //         hash = CryptoJS.MD5(input).toString(),
        //         color = hash.substr(0, 6),
        //         colorNew = 4095;
        //     let hashes = [];
        //     const average = arr => arr.reduce((sume, el) => sume + el, 0) / arr.length;

        //     for(let i = 0; i < 5; i++){
        //         hashes.push(parseInt(hash.substr(6*i, 6), 16));
        //     }
        //     colorNew = Math.round(average(hashes));

        //     console.log(hashes);

        //     console.log(input, hash, color, colorNew, colorNew.toString(16));
        //     console.log ( '%c%s', `background-color: #${color};`, input );
        //     console.log ( '%c%s', `background-color: #${colorNew.toString(16)};`, input );
        // },

        //for my account
        onEmailInput(){
            let button = document.getElementById('changeEmailButton'),
                input = document.getElementById('changeEmailInput');
            input.classList.remove('is-success');

            if(this.user.email != this.newUserEmail && this.validateEmail(this.newUserEmail)){
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

        loadChannels(){
            return axios.get(`/channel/all`, {
                    headers: {
                        Authorization: 'Bearer ' + localStorage.getItem('token')
                        }
                    }
                ).then(response => {
                    // console.log(response);
                    let list = response.data.filter(channel => channel.type != 'dm');
                    list.forEach(ch => {
                        if(this.$parent.channelList.find(channel => channel.id == ch.id)){
                            if(ch.deleted_at)
                                this.$set(this.$parent.channelList.find(channel => channel.id == ch.id), 'deleted_at', ch.deleted_at);
                        }else{
                            ch.deleted = true;
                            this.$parent.channelList.push(ch);
                        }
                    });
                    // this.chList = response.data.filter(channel => channel.type != 'dm');
                    this.$parent.channelList.sort((a, b) => {
                        return a.position - b.position;
                    });
                }).catch(error => {
                    console.error(error);
                    // console.log(error.response);
                });
        },
        restoreChannel(id){
            axios.get(`/channel/${id}/undelete`, {
                    headers: {
                        Authorization: 'Bearer ' + localStorage.getItem('token')
                        }
                    }
                ).then(response => {
                    // console.log(response);
                }).catch(error => {
                    console.error(error);
                    // console.log(error.response);
                });
        },

        changeUser(e){
            // console.log(this.currentUser, e);
            const change = function(vm){
                vm.currentUser = e.id;
                vm.userOriginalValues = {};
                vm.clearUserRoles();
                vm.permissionList.forEach(perm => {
                    if(vm.userList.find(user => user.id == vm.currentUser).permissions.find(p => p.id == perm.id)){
                        perm.checked = true;
                    }else{
                        perm.checked = false;
                    }
                    vm.userOriginalValues[`${perm.name}-${perm.id}`] = perm.checked;
                });
                vm.roleList.forEach(role => {
                    if(vm.userList.find(user => user.id == vm.currentUser).roles.find(p => p.id == role.id)){
                        role.checked = true;
                    }else{
                        role.checked = false;
                    }
                    vm.userOriginalValues[`${role.name}-${role.id}`] = role.checked;
                });
            };
            if(this.currentUser == e.id){
                return;
            }
            if(this.currentUser == 0){
                change(this);
            }
            let currentUser = this.userList.find(user => user.id == this.currentUser);
            if(!currentUser){
                change(this);
                return;
            }
            // console.log(currentRole.permissions);
            if(this.isUserDiferent(this.roleList, this.permissionList, this.userList.find(user => user.id == this.currentUser))){
                // console.log('is diferent');
            }else{
                change(this);
            }
            // change(this);
        },
        checkUserRolesInput(){
            this.showUserRoleSaveDialog = this.isUserDiferent(this.roleList, this.permissionList, this.userList.find(user => user.id == this.currentUser));
            // console.log(this.isUserDiferent(this.roleList, this.permissionList, this.userList.find(user => user.id == this.currentUser)));
            // console.log(this.roleList, this.permissionList, this.userList.find(user => user.id == this.currentUser));
        },
        isUserDiferent(a, b, c){
            let isDiferent = false;

            // console.log('roles', a, c.roles);
            // console.log('permissions', b, c.permissions);
            // console.log('original', this.userOriginalValues);

            let curentValues = {};
            this.permissionList.forEach(perm => {
                curentValues[`${perm.name}-${perm.id}`] = perm.checked;
            });
            this.roleList.forEach(role => {
                curentValues[`${role.name}-${role.id}`] = role.checked;
            });

            Object.keys(this.userOriginalValues).forEach(key => {
                if(this.userOriginalValues[key] != curentValues[key]){
                    isDiferent = true;
                }
            });

            // console.log('new', curentValues);
            // console.log('isDiferent', isDiferent);

            return isDiferent;
        },
        saveUserRoles(){
            let roles = [],
                permissions = [],
                userId = this.currentUser;

            this.permissionList.forEach(perm => {
                if(perm.checked){
                    permissions.push(perm.id);
                }
            });
            this.roleList.forEach(role => {
                if(role.checked){
                    roles.push(role.id);
                }
            });

            // console.log(roles, permissions);
            axios.post(`/user/${userId}`, {
                        roles: roles,
                        permissions: permissions,
                    }, {
                        headers: {
                            Authorization: 'Bearer ' + localStorage.getItem('token')
                        }
                    }
                ).then(response => {
                    // console.log(response);
                    this.showUserRoleSaveDialog = false;
                    this.userOriginalValues = {};
                    this.$nextTick(() => {
                        this.permissionList.forEach(perm => {
                            if(this.userList.find(user => user.id == this.currentUser).permissions.find(p => p.id == perm.id)){
                                perm.checked = true;
                            }else{
                                perm.checked = false;
                            }
                            this.userOriginalValues[`${perm.name}-${perm.id}`] = perm.checked;
                        });
                        this.roleList.forEach(role => {
                            if(this.userList.find(user => user.id == this.currentUser).roles.find(p => p.id == role.id)){
                                role.checked = true;
                            }else{
                                role.checked = false;
                            }
                            this.userOriginalValues[`${role.name}-${role.id}`] = role.checked;
                        });
                    });
                    this.$set(this.userList.find(user => user.id == userId), 'permissions', response.data.permissions);
                    this.$set(this.userList.find(user => user.id == userId), 'roles', response.data.roles);
                    this.resetUserRoles();
                }).catch(error => {
                    console.error(error);
                    // console.log(error.response);
                });
        },
        resetUserRoles(){
            this.permissionList.forEach(perm => {
                if(this.userList.find(user => user.id == this.currentUser).permissions.find(p => p.id == perm.id)){
                    perm.checked = true;
                }else{
                    perm.checked = false;
                }
            });
            this.roleList.forEach(role => {
                if(this.userList.find(user => user.id == this.currentUser).roles.find(p => p.id == role.id)){
                    role.checked = true;
                }else{
                    role.checked = false;
                }
            });
            this.showUserRoleSaveDialog = false;
        },
        clearUserRoles(){
            this.permissionList.forEach(perm => {
                perm.checked = false;
            });
            this.roleList.forEach(role => {
                role.checked = false;
            });
            this.checkUserRolesInput();
        },
        resetAvatar(){
            axios.get(`/user/image/reset`, {
                        headers: {
                            Authorization: 'Bearer ' + localStorage.getItem('token')
                        }
                    }
                ).then(response => {
                    // console.log(response);
                    this.user.image = `${response.data.image}?${Math.random()}`;
                }).catch(error => {
                    console.error(error);
                    // console.log(error.response);
                });
        },
        handleFileChange(e){
            // console.log('input changed');
            let image = e.target.files[0];
            // console.log(image);

            if(image.size / 1024 > 8192){
                this.$set(this.error, 'file', 'The file may not be greater than 8192 kilobytes.');
                return;
            }

            if(image){
                this.$set(this.error, 'file', undefined);
    
                let formData = new FormData();
                formData.append('file', image);
    
                axios.post(`/user/image`, formData, {
                            headers: {
                                Authorization: 'Bearer ' + localStorage.getItem('token'),
                                'Content-Type': 'multipart/form-data',
                            }
                        }
                    ).then(response => {
                        // console.log(response);
                        // console.log(this.user.image);
                        // console.log(response.data.image);
                        this.user.image = `${response.data.image}?${Math.random()}`;
                        // this.$set(this.user, 'image', response.user.image);
                    }).catch(error => {
                        console.error(error);
                        // console.log(error.response);
                        // console.log(error.response.data.errors.file.join(' '));
                        this.$set(this.error, 'file', error.response.data.errors.file.join(' '));
                        // console.log(this.error);
                    });

            }
        }
    },
}
</script>

<style scoped>
    .menuRoot{
        position: fixed;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
    }
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
    .is-clickable {
        cursor: pointer;
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
    .fullHeight{
        height: 100%;
    }
    .fOptionsPadding{
        padding: 40px 30px 30px 30px;
    }
    .fSettingsSideMenu{
        width: 200px;
    }
    .fMenuScroll{
        overflow-y: auto;
    }
    .fRole:hover .fRoleIcon{
        visibility: visible;
    }
    .fRoleIcon{
        visibility: hidden;
        transform: translateY(-27px);
    }
    .menu-list a:hover:not(.is-active){
        background-color: hsla(0, 0%, 0%, 0.05) !important;
        /* color: #222 !important; */
    }
    .is-active{
        background-color: hsla(0, 0%, 0%, 0.1) !important;
        color: inherit;
    }
    hr.rounded{
        border-top: 1px solid #ddd;
        border-radius: 100px;
        margin: 5px;
    }
    .file-cta:hover .file-label{
        color: whitesmoke;
    }
    .file-cta:hover .file-icon{
        color: #f00;
    }
    .file-cta:hover .file-bg{
        background-color: #80808080;
        width: 100%;
        height: 100%;
    }
    .file-bg{
        position: absolute;
        top: 0;
        width: 100%;
        height: 100%;
        border-radius: 100%;
        background-color: none;
    }
    .file-icon, .file-label{
        color: transparent;
    }
    .fade-in-enter-active {
        transition: all .2s ease;
    }
    .fade-in-leave-active {
        transition: all .1s cubic-bezier(1.0, 0.5, 0.8, 1.0);
    }
    .fade-in-enter, .fade-in-leave-to {
        /* transform: translateY(-10px); */
        opacity: 0;
    }
</style>>