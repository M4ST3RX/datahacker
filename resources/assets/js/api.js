export default class API {

    constructor(){

    }

    sendCommand(input){
        let self = this;
        let command_parts = input.split(" ");
        let cmd = command_parts[0];
        let cmd_args = [];
        if(command_parts.length > 1){
            cmd = command_parts.shift();
            cmd_args = command_parts;
        }
        if(cmd != "ls"){
            $.ajax("/api/terminal", {
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    cmd: cmd,
                    args: cmd_args
                }
            }).done(function(data){
                data = JSON.parse(data);
                if(data){
                    if(data.msg !== undefined) self.outputTerminal(input, data.msg);
                    self.processAfter(data);
                } else {
                    console.log('PHP error.');
                }
            });
        } else {
            if(cmd == "ls"){
                let table = "<table width=\"100%\"><th>ID</th><th>Name</th><th>Type</th>";
                window.local_files_cache.forEach(function(file){
                    table += "<tr><td>"+file.id+"</td><td>"+file.file_name+"</td><td>"+file.type.type+"</td></tr>"
                });
                table += "</table>";
                self.outputTerminal(input, table);
            }
        }
    }

    getHistoryList(vueComponent){
        $.ajax("/api/history/list", {
            type: "GET",
        }).done(function(data){
            data = JSON.parse(data);
            if(data){
                vueComponent.setList(data);
            } else {
                console.log('PHP error.');
            }
        });
    }

    finishProcess(id){
        let self = this;
        $.ajax("/api/taskmanager/finish", {
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                id: id
            }
        }).done(function (data) {
            data = JSON.parse(data);
            if (data) {
                //self.outputTerminal("", data.msg);
                if(!data.error){
                    //window.vue.taskManager.removeProcess(id);
                    if(data.last_process.type == "crack"){
                        window.vue.history.addIP(data.last_process.related_ip);
                    }
                }
            } else {
                console.log('PHP error.');
            }
        });
    }

    cancelProcess(id){
        $.ajax("/api/taskmanager/delete", {
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                id: id
            }
        }).done(function (data) {
            if (data) {
                window.vue.taskManager.removeProcess(id);
            } else {
                console.log('PHP error.');
            }
        });
    }

    getRunningProcesses(){
        $.ajax("/api/taskmanager/all", {
            type: "GET",
        }).done(function (data) {
            data = JSON.parse(data);
            if (data) {
                window.vue.taskManager.setProcessesData(data['cpu_data'], data['network_data']);
            } else {
                console.log('PHP error.');
            }
        });
    }

    outputTerminal(cmd, msg){
        let terminal_dump = $("#term_dump");
        terminal_dump.append($('#start_term').html() + '&nbsp;' + cmd.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;') + '<br>');
        terminal_dump.append('<br><span style="color: #f99f4a; font-weight: bold;">' + msg + '</span><br><br>');
    }

    processAfter(data){
        let terminal_dump = $("#term_dump");
        if(data['function']){
            if(data['function'] == "setConnectedTo"){
                localStorage.connectedTo = data['ip'];
                $("#start_term").html(localStorage.connectedTo + "<i class=\"terminal_chevron fa fa-chevron-right\" style=\"font-size:12px;\" aria-hidden=\"true\"></i>");
                $("#term_command").css("padding-left", ($("#start_term").width() + 5).toString() + "px");
            }
        }
        if (data['process_cpu']) {
            window.vue.taskManager.setData(data);
        } else {
            window.vue.taskManager.setData(data);
        }

        while (terminal_dump.contents().length > 100) {
            terminal_dump.contents().first().remove();
        }
    }

    addToList(ip){
        $.ajax("/api/history/add", {
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                ip: ip
            }
        }).done(function (data) {
            data = JSON.parse(data);
            if (data) {
                window.vue.history.addToList(data.list);
            } else {
                console.log('PHP error.');
            }
        });
    }
}
