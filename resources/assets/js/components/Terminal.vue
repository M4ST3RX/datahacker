<template>
    <div @click="setFocus()" id="terminal" :class="{ open: isOpen }" class="window ui-draggable ui-resizable" :style="{ zIndex: 1, backgroundColor: this.os.terminal_color, fontSize: '14px', width: '400px', height: '300px' }">
        <div class="window-header" :style="{ backgroundColor: this.os.header_color, cursor: 'context-menu'}">
            <div id="icon_placement_Logs" style="display: inline;">
                <i class="fa fa-fw fa-list" style="margin-left: 4px;" aria-hidden="true"></i>
            </div>
            Terminal
            <div class="pull-right" style="">
                <i class="far fa-window-minimize header-button" aria-hidden="true"></i>
                <i class="far fa-window-maximize header-button" aria-hidden="true"></i>
                <i class="far fa-window-close header-button" @click="$parent.closeWindow(name)" aria-hidden="true"></i>
            </div>
        </div>
        <div class="block" :style="{width: '100%', height: '90%', overflow: 'auto', padding: '0 0 23px 0', backgroundColor: this.os.terminal_color, flex: '1 1 auto' }">
            <div id="dialog_term" style="padding: 2px; margin: 0; color: #fff;">
                <div class="term col-md-12" style="padding: 2px; margin: 0; font-size: 11px; color: white; font-weight: bold;">
                    <div id="term_dump" style="overflow-wrap: break-word; font-weight: normal; color: white;"></div>
                    <span style="position: absolute; margin-top: 2px; font-weight: normal; color: white; display: block;" id="start_term">
                        <i class="terminal_chevron fa fa-chevron-right" style="font-size:12px;" aria-hidden="true"></i>
                    </span>
                    <input v-model="command" type="text" maxlength="255" id="term_command" autocomplete="off" ref="input" @keyup.enter="sendCommand()" class="terminal_input" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import API from "../api.js"
    export default {
        props: {
            os: Object
        },
        data() {
            return {
                isOpen: false,
                name: "terminal",
                command: ''
            };
        },
        methods: {
            sendCommand(){
                let api = new API();
                api.sendCommand(this.command);
                this.command = "";
            },
            closeWindow(){
                this.$parent.closeWindow(name);
            },
            setFocus(){
                this.$refs.input.focus();
            }
        }
    }
</script>
