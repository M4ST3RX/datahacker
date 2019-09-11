<template>
    <div id="task_manager" :class="{ open: isOpen }" class="window ui-draggable ui-resizable" :style="{ backgroundColor: this.os.window_color, fontSize: '14px', width: '400px', height: '300px', zIndex: '2' }">
        <div class="window-header" :style="{ backgroundColor: this.os.header_color, cursor: 'context-menu' }">
            <div id="icon_placement_Task Manager" style="display: inline;">
                <i class="fa fa-fw fa-tasks" style="margin-left: 4px;" aria-hidden="true"></i>
            </div>
            Task Manager
            <div class="pull-right" style="">
                <i class="far fa-window-minimize header-button" aria-hidden="true"></i>
                <i class="far fa-window-maximize header-button" aria-hidden="true"></i>
                <i class="far fa-window-close header-button" aria-hidden="true" @click="$parent.closeWindow(name)"></i>
            </div>
        </div>
        <div class="block" :style="{ width: '100%', height: '100%', padding: '0 0 23px', backgroundColor: this.os.window_color, overflow: 'auto' }">
            <div class="tabs" style="display: flex;">
                <div class="tab-cpu tab-style tab-style-selected" :style="{ margin: '1px', backgroundColor: this.os.tab_color }">
                    <h6 class="text-white" style="margin-top: 5px; color: white;">CPU</h6>
                </div>
                <div class="tab-network tab-style" :style="{ margin: '1px', backgroundColor: this.os.tab_color }">
                    <h6 class="text-white" style="margin-top: 5px; color: white;">Network</h6>
                </div>
            </div>
            <div class="process_inner" id="process_cpu" style="overflow: auto; height: auto; color: white;">
                <table width="100%" style="border-collapse: separate; border-spacing: 0 3px;">
                    <tbody>
                        <tr v-for="cpu_proc in cpu_processes">
                            <td style="cursor: pointer;" :id="'process_' + cpu_proc.id" class="process_action_container" @click="cancelProcess(cpu_proc.id)" width="10%">
                                <i class="fa fa-times process_action" aria-hidden="true"></i>
                            </td>
                            <td width="80%" style="background: rgba(0,166,198,0.5);">
                                <div :id="'process_change_' + cpu_proc.id" data="running" :data_type="cpu_proc.type" class="process_fill progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="background-color: rgb(0, 100, 130); transition: none !important;height:100%;width: 0%">
                                    <div class="progress_item" style="width:100%;overflow:hidden;">
                                        {{ cpu_proc.ip }}
                                    </div>
                                </div>
                            </td>
                            <td :id="'process_id_' + cpu_proc.id" @click="finishProcess(cpu_proc.id)" class="process_type" width="10%"><i class="fas fa-check"></i></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="process_inner hide" id="process_network" style="overflow: auto; height: auto; color: white;"></div>
            <br style="color: white;">
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
    data() {
        return {
            isOpen: false,
            name: "taskManager",
            cpu_processes: [],
            network_processes: []
        };
    },
    mounted(){
        api.getRunningProcesses();
    },
    methods: {
        closeWindow(){
            this.$parent.closeWindow(name);
        },
        setData(data){
            if(data['process_cpu']){
                this.cpu_processes.push(data['process_data']);
            } else {
                this.network_processes.push(data['process_data']);
            }
        },
        finishProcess(id){
            api.finishProcess(id);
        },
        cancelProcess(id){
            api.cancelProcess(id);
        },
        removeProcess(id){
            let found = false;
            let self = this;
            this.cpu_processes.forEach(function(data, index){
                if(data.id == id){
                    found = true;
                    self.cpu_processes.splice(index,1);
                }
            });
            if(!found){
                this.network_processes.forEach(function(data, index){
                    if(data.id == id){
                        found = true;
                        self.cpu_processes.splice(index,1);
                    }
                });
            }
        },
        setProcessesData(cpu, network){
            this.cpu_processes = cpu;
            this.network_processes = network;
        }
    }
}
</script>
