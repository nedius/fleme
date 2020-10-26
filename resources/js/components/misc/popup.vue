<template>
    <transition name="fade" appear>
        <div id="popupRoot" ref="popupRoot" class="popup has-background-grey-light" v-on-clickaway="closeModal">
            <figure class="image is-128x128 is-rounded popupUserImage">
                <img :src="data.user.image" style="border-radius: 290486px;">
            </figure>

            <p class="title is-5 popupUserName popupWrapText"> {{ userName }} </p>

            <div class="popupMisc">
                <div v-if="this.$parent.user.id != data.user.id" class="button is-fullwidth popupWrapText" style="margin-left: 10px; margin-right: 10px; width: 90%;" @click="openDmChannel">Message @{{ userName.substr(0,9) }}</div>
                <div v-else style="text-align: center;">It's you</div>
            </div>

            <!-- <div>
                event {{ this.event.width }} ~ {{ this.event.height }}
            </div>
            <div>
                screen {{ this.window.width }} ~ {{ this.window.height }}
            </div>
            <div>
                popup {{ this.popup.clientWidth }} ~ {{ this.popup.clientHeight }}
            </div>
            <div>
                offset {{ this.offset.width }} ~ {{ this.offset.height }}
            </div>
            <div>
                finish {{ this.finish.width }} ~ {{ this.finish.height }}
            </div> -->
        </div>
    </transition>
</template>

<script>
import { mixin as clickaway } from 'vue-clickaway';

export default {
    name: 'popup',
    mixins: [ clickaway ],
    props: [
        'data',
        'type',
    ],
    data() {
        return {
            popup: Object,
            userName: '',
            event: {
                width: 0,
                height: 0,
            },
            window: {
                width: 0,
                height: 0,
            },
            offset: {
                width: 0,
                height: 0,
            },
            finish: {
                width: 0,
                height: 0,
            },
        }
    },
    mounted() {
        // console.log(this.type, this.data);
        // console.log(this.data.event, this.data.event.srcElement.getBoundingClientRect().top, this.data.event.srcElement.getBoundingClientRect().left);

        if(this.data.user.name.lenght > 18){
            this.userName = `${this.data.user.name.substr(0, 18)}...`;
        }else{
            this.userName = this.data.user.name;
        }

        this.popup = this.$refs.popupRoot;
        let eventX = this.data.event.srcElement.getBoundingClientRect().left,
            eventY = this.data.event.srcElement.getBoundingClientRect().top,
            screenX = document.body.scrollWidth,
            screenY = document.body.scrollHeight,
            popupX = this.popup.clientWidth,
            popupY = this.popup.clientHeight,
            offsetX = 0,
            offsetY = 0;

        this.window.width = screenX;
        this.window.height = screenY;

        if(eventX + popupX <= 0 ){
            offsetX = -popupX;
        }else if(eventX + popupX >= screenX ){
            offsetX = popupX;
        }

        if(eventY + popupY <= 0 ){
            offsetY = -popupY;
        }else if(eventY + popupY >= screenY ){
            offsetY = popupY;
        }

        if(offsetX == 0 && eventX - this.data.event.srcElement.offsetWidth > 0 && eventX - this.data.event.srcElement.offsetWidth < screenX){
            if(this.data.event.srcElement.offsetWidth > 100){
                offsetX = -(this.data.event.srcElement.offsetWidth/2);
            }else{
                offsetX = -this.data.event.srcElement.offsetWidth;
            }
        }
        if(offsetY == 0 && eventY - this.data.event.srcElement.offsetHeight > 0 && eventY - this.data.event.srcElement.offsetHeight < screenY && this.data.event.srcElement.offsetHeight > 100){
            if(this.data.event.srcElement.offsetWidth > 100){
                offsetY = -(this.data.event.srcElement.offsetHeight/2);
            }else{
                offsetY = -this.data.event.srcElement.offsetHeight;
            }
        }

        this.offset.width = offsetX;
        this.offset.height = offsetY;
        this.finish.width = eventX - offsetX; 
        this.finish.height = eventY - offsetY;
        this.event.width = eventX; 
        this.event.height = eventY;

        this.popup.style.top = `${eventY - offsetY}px`;
        this.popup.style.left = `${eventX - offsetX}px`;
   
    },
    methods: {
        closeModal(){
            this.$parent.isPopupVisible = false;
            // this.$emit('close');
        },
        hasPermissions(permission){
            let can = false;
            try{
                this.data.user.permissions.forEach(perm => {
                    if(perm.slug == permission){
                        can = true;
                    }
                });
            }catch(e){
                // 
            }
            return can;
        },
        openDmChannel(){
            this.$parent.openDmChannel(this.data.user);
            this.closeModal();
        },
    },
}
</script>

<style scoped>

    #popupRoot{
        position: fixed;
        top: 30px;
        left: 30px;

        width: 200px;
        height: 250px;
    }
    .popup {
        /* display: fixed; */
        border-radius: 10px;
    }
    .popupUserImage{
        margin: auto;
        margin-top: 10px;
    }
    .popupUserName{
        margin-top: 10px;
        text-align: center;
    }
    .popupMisc{
        margin-top: 10px;
    }
    .popup-is-centered{
        margin: auto;
    }
    .popupWrapText{
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
        margin-left: 10px;
        margin-right: 10px;
    }


    .fade-enter-active {
        transition: opacity .1s ease;
    }
    .fade-leave-active {
        transition: opacity .1s cubic-bezier(1.0, 0.5, 0.8, 1.0);
    }
    .fade-enter, .fade-leave-to {
        /* transform: translateY(-10px); */
        opacity: 0;
    }
    
</style>>