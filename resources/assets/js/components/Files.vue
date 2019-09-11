<template>
    <div id="files" :class="{ open: isOpen }" class="window ui-draggable ui-resizable" :style="{ zIndex: '1', backgroundColor: this.os.window_color, fontSize: '14px', width: '400px', height: '300px' }">
        <div class="window-header" :style="{ backgroundColor: this.os.header_color, cursor: 'context-menu' }">
            <div id="icon_placement_Logs" style="display: inline;">
                <i class="far fa-fw fa-folder" style="margin-left: 4px;" aria-hidden="true"></i>
            </div>
            File Manager
            <div class="pull-right" style="">
                <i class="far fa-window-minimize header-button" aria-hidden="true"></i>
                <i class="far fa-window-maximize header-button" aria-hidden="true"></i>
                <i class="far fa-window-close header-button" @click="$parent.closeWindow(name)" aria-hidden="true"></i>
            </div>
        </div>
        <div class="block" :style="{ width: '100%', height: '90%', overflow: 'auto', padding: '0 0 23px 10px', backgroundColor: this.os.window_color, flex: '1 1 auto' }">
        </div>
    </div>
</template>

<script>
export default {
    props: {
        os: Object
    },
    data(){
        return {
            isOpen: false,
            name: "files"
        }
    },
    methods: {
        closeWindow(){
            this.$parent.closeWindow(name);
        }
    }
}
</script>
