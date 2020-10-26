<template>
    <div v-if="!this.msg.deleted" class="msgRoot">
        <div class="msgWrapper">
            <figure class="image is-48x48">
                <img class="is-rounded"  :src="`${ this.msg.user.image }`" @click="openUser">
            </figure>
            <div class="msgDiv">
                <div>
                    <span class="msgName" @click="openUser">{{ this.msg.user.name }}</span>
                    <span class="msgDate">{{ this.createdString }}</span>
                    <span v-if="(iCanEdit || iCanDelete)" class="is-pulled-right msgIcons">
                        <font-awesome-icon icon="pencil-alt" v-if="!editing && iCanEdit" class="is-clickable" @click="editing = !editing; editString=msg.content; editFix()"/>
                        <font-awesome-icon icon="trash-alt" v-if="!editing && iCanDelete" class="is-clickable" @click="deleteMessage"/>
                    </span>
                </div> 
                <div v-if="!editing" class="msgContent">{{ this.msg.content }} <span class="msgDate" v-if="this.updated" > (updated) </span> </div>
                <div v-if="editing">
                    <textarea :id="`fixTextArea${this.msg.id}`" ref='msgEdit' class="textarea msgEdit" role="textbox" v-model.trim="editString" @input="handleEdit" @change="handleEdit" @keypress="onInputMessage" @keydown.esc="editing = !editing;"></textarea>
                    <span class="has-text-link link" @click="editing = !editing">cancel</span> or <span class="has-text-link link" @click="sendEditMessage">save</span>
                </div>
                <div v-if="hasFiles && !editing">
                    <div v-for="file in msg.files" :key="file.id">

                        <img v-if="file.type == 'image'" :src="file.url" alt="" class="msgImage" :style="`height: ${file.height ? file.height : '300'}px; cursor: pointer;`" @click="openImageModal(file)">
                        <video v-else-if="file.type == 'video'" :src="file.url" class="msgImage" :style="``" controls></video>
                        <audio v-else-if="file.type == 'audio'" :src="file.url" class="msgImage" :style="`height: 50px; width: 400px; outline: 0;`" controls></audio>
                        <div v-else>
                            <div class="box" style="margin-bottom: 5px; max-width: 500px;">
                                <article class="media">
                                    <div class="media-left">
                                        <font-awesome-icon icon="file" style="font-size: 3em;"/>
                                    </div>
                                    <div class="media-content" style="margin: auto;">
                                        <div class="content">
                                            <a :href="file.url" target="_blank" rel="noopener noreferrer" style="word-break: break-all;">{{ file.url.split('\\').pop().split('/').pop() }}</a>
                                        </div>
                                    </div>
                                </article>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        
        <transition name="fade" appear>
            <div v-if="showImageModal" class="msgImageModalDiv" @click="closeImageModal">
                <div class="msgImageModalWrapper">
                    <img class="msgImageModal" :src="imageModal" alt="">
                    <a :href="imageModal" target="_blank" rel="noopener noreferrer" style="display: block;">Open in new tab</a>
                </div>
            </div>
        </transition>

    </div>
</template>

<script>
export default {
    name: 'message',
    props: [
        'msg',
        'myMessage',
        'canEdit',
        'canDelete',
        'canEditOthers',
        'canDeleteOthers',
    ],
    data() {
        return {
            created: undefined,
            createdString: undefined,
            updated: undefined,
            updatedString: undefined,

            editing: false,
            editString: '',

            iCanEdit: false,
            iCanDelete: false,

            hasFiles: false,

            showImageModal: false,
            imageModal: '',
        }
    },
    mounted() {
        this._mounted();
    },
    watch:{
        'canEdit': function (){
            this.checkPerms();
        },
        'canDelete': function (){
            this.checkPerms();
        },
        'canEditOthers': function (){
            this.checkPerms();
        },
        'canDeleteOthers': function (){
            this.checkPerms();
        },
    },
    methods: {
        _mounted(){
            // console.log(this.msg);

            let updated,
                temp;

            // this.editString = this.msg.content;

            this.created = this.timeToLocal(new Date(this.msg.created_at));
            temp = this.dateToObj(this.created);
            this.createdString = `${this.getFancyDate(this.created)} ${temp.hour}:${temp.minute}${this.isToday(this.created) ? `:${temp.second}` : ``}`;
            updated = this.timeToLocal(new Date(this.msg.updated_at));

            if(updated > this.created){
                this.updated = updated;
                temp = this.dateToObj(this.updated);
                this.createdString = `${temp.day < 10 ? `0${temp.day}` : temp.day}/${temp.month < 10 ? `0${temp.month}` : temp.month}/${temp.year} ${temp.hour}:${temp.minute}:${temp.second}`;
            }

            this.checkPerms();

            // console.log(`iCanEdit:${this.iCanEdit}, iCanDelete:${this.iCanDelete}, myMessage:${this.myMessage}, canEdit:${this.canEdit}, canDelete:${this.canDelete}, canEditOthers:${this.canEditOthers}, canDeleteOthers:${this.canDeleteOthers}`);

            // console.log(this.created, updated);

            if(this.msg.files.length > 0){
                this.hasFiles = true;
            }
        },
        checkPerms(){
            if((this.canEdit && this.myMessage) || this.canEditOthers){
                this.iCanEdit = true;
            }else{
                this.iCanEdit = false;
            }
            if((this.canDelete && this.myMessage) || this.canDeleteOthers){
                this.iCanDelete = true;
            }else{
                this.iCanDelete = false;
            }
        },
        dateToObj(date){
            let monthNames = [
                    "January",
                    "February",
                    "March",
                    "April",
                    "May",
                    "June",
                    "July",
                    "August",
                    "September",
                    "October",
                    "November",
                    "December"
                ];
            return {
                        year: date.getFullYear(),
                        month: date.getMonth()+1,
                        monthWord: monthNames[date.getMonth()],
                        day: date.getDate(),
                        hour: date.getHours(),
                        minute: date.getMinutes(),
                        second: date.getSeconds(),
                        milisecond: date.getMilliseconds()
                    };
        },
        timeToLocal(date) {
            var newDate = new Date(date.getTime()+date.getTimezoneOffset()*60*1000);
            newDate.setHours(date.getHours() - (date.getTimezoneOffset() / 60));
            return newDate;   
        },
        getFancyDate(date){
            let str = ``,
                temp = this.dateToObj(this.created),
                yesterday = _.clone(date);
            yesterday.setDate(yesterday.getDate() + 1);

            if(this.isToday(date)){
                str = `Today at`;
            }else if(this.isToday(yesterday)){
                str = `Yesterday at`;
            }else{
                str = `${temp.day < 10 ? `0${temp.day}` : temp.day}/${temp.month < 10 ? `0${temp.month}` : temp.month}/${temp.year}`;
            }
            // ${this.isToday(this.created)? `Today at` : `${temp.day < 10 ? `0${temp.day}` : temp.day}/${temp.month < 10 ? `0${temp.month}` : temp.month}/${temp.year}` }
            return str;
        },
        isToday(someDate){
            const today = new Date();
            return someDate.getDate() == today.getDate() &&
                someDate.getMonth() == today.getMonth() &&
                someDate.getFullYear() == today.getFullYear();
        },
        editFix(){
            this.$nextTick(() => {
                let e = {};
                // console.log(document.getElementById(`fixTextArea${this.msg.id}`));
                e.target = document.getElementById(`fixTextArea${this.msg.id}`);
                this.handleEdit(e);
                this.$refs.msgEdit.focus();
            });
        },
        handleEdit(e){
            // console.log(e);
            let el = e.target;

            el.style.height = "5px";
            el.style.height = (el.scrollHeight)+"px";
            
            if(el.scrollHeight > 208)
                el.style.overflowY = "scroll";
            else
                el.style.overflowY = "hidden";
        },
        onInputMessage(e){
            if(e.key === "Enter"){
                if(!e.shiftKey){
                    e.preventDefault();
                    if(!(isEmpty(this.editString.trim()) || isBlank(this.editString.trim()) || this.editString.trim().isEmpty())){  
                        this.sendEditMessage();
                    }
                }
            }
        },
        sendEditMessage(){
            if(this.editString != this.msg.content){
                axios.put(`message/${this.msg.channel.id}/${this.msg.id}`,
                    {content: this.editString},
                    {headers: { Authorization: 'Bearer ' + localStorage.getItem('token')}}
                ).then(response => {
                    // console.log(response)
                    this.editing = false;

                    this.msg.content = response.data.content;
                    this.msg.created_at = response.data.created_at;
                    this.msg.deleted_at = response.data.deleted_at;
                    this.msg.updated_at = response.data.updated_at;

                    this._mounted();
                }).catch(error => {
                    console.error(error);
                    console.log(error.response);
                });
            }else{
                this.editing = false;
            }
        },
        deleteMessage(){
            // console.log(this.msg);
            axios.delete(`message/${this.msg.channel.id}/${this.msg.id}`,
                {headers: { Authorization: 'Bearer ' + localStorage.getItem('token')}}
            ).then(response => {
                // console.log(response)
            }).catch(error => {
                console.error(error);
                console.log(error.response);
            });
        },
        openUser(e){
           this.$parent.openUser(e, this.msg.user);
        },
        openImageModal(file){
            this.imageModal = file.url;
            this.showImageModal = true;
        }, 
        closeImageModal(e){
            // console.log(e)
            if(e.target.classList.contains('msgImageModalDiv'))
                this.showImageModal = false;
        }
    },
}
</script>

<style scoped>
    .msgWrapper{
        margin: 5px 10px 20px 10px;
        overflow: hidden;
        display: flex;
    }
    .msgDiv{
        flex-grow: 1;
        margin-left: 5px;
    }
    .msgContent{
        user-select: text;
        white-space: pre-line;
        word-break: break-word;
        overflow: hidden;
    }
    .msgName{
        user-select: text;
        overflow: hidden;
    }
    .msgDate{
        user-select: text;
        font-size: .8em;
        font-weight: lighter;
    }
    .is-clickable {
        cursor: pointer;
    }
    .msgEdit{
        /* min-height: 10px;
        height: auto; */

        resize: none;
        overflow-x: hidden;
        overflow-y: hidden;
        min-height: 50px;
        max-height: 208px;
    }
    .msgImage{
        height: auto;
        width: auto;
        max-width: 400px;
        max-height: 300px;
        border-radius: 5px;
    }
    .link{
        cursor: pointer;
    }
    .link:hover{
        text-decoration: underline;
    }
    .msgRoot:hover{
        background-color: rgba(0, 0, 0, 0.02);
    }
    .msgIcons{
        visibility: hidden;
    }
    .msgRoot:hover .msgIcons{
        visibility: visible;
    }
    .msgImageModalDiv{
        background-color: #000000e6;
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 50;
    }
    .msgImageModalWrapper{
        align-items: center;
        justify-content: center;
        overflow: hidden;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
    .msgImageModal{
        width: auto;
        height: auto;
        max-width: 800px;
        max-height: 600px;
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
</style>>