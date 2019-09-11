<template>
    <div id="history" :class="{ open: isOpen }" class="window ui-draggable ui-resizable" :style="{ zIndex: '1', backgroundColor: this.os.window_color, fontSize: '14px', width: '400px', height: '300px' }">
        <div class="window-header" :style="{ backgroundColor: this.os.header_color, cursor: 'context-menu' }">
            <div id="icon_placement_Logs" style="display: inline;">
                <i class="far fa-fw fa-address-book" style="margin-left: 4px;" aria-hidden="true"></i>
            </div>
            History
            <div class="pull-right" style="">
                <i class="far fa-window-minimize header-button" aria-hidden="true"></i>
                <i class="far fa-window-maximize header-button" aria-hidden="true"></i>
                <i class="far fa-window-close header-button" @click="$parent.closeWindow(name)" aria-hidden="true"></i>
            </div>
        </div>
        <div class="block" :style="{ width: '100%', height: '90%', overflow: 'auto', padding: '0 0 23px 10px', backgroundColor: this.os.window_color, flex: '1 1 auto' }">
            <table width="100%">
                <tbody style="text-align: center;">
                    NPCs
                </tbody>
                <tbody id="npc_list" v-for="item in list">
                    <tr>
                        <td>{{ item.ip }}<br>{{ item.name }}</td>
                        <td>{{ item.password }}</td>
                        <td>{{ item.action }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import API from '../api.js';
let api = new API();

export default {
    props: {
        os: Object
    },
    data(){
        return {
            isOpen: false,
            name: "history",
            list: []
        }
    },
    beforeMount(){
        api.getHistoryList(this);
    },
    methods: {
        closeWindow(){
            this.$parent.closeWindow(name);
        },
        setList(data){
            this.list = data.list;
        },
        addToList(data){
            this.list.push(data);
        },
        addIP(ip){
            api.addToList(ip);
        }
    }
}
</script>
