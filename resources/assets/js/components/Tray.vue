<template>
    <div class="game-menu" id="tray" :style="{width: '100%', position: 'fixed', bottom: '0', fontSize: '14px', color: 'white', backgroundColor: this.os.header_color, zIndex: '9999'}">
        <tray-icon id="menu_start" name="start" icon="fa-biohazard" type="fas" color="rgb(0, 255, 33)"></tray-icon>
        <tray-icon id="menu_terminal" name="terminal" icon="fa-list" type="fas" color="white"></tray-icon>
        <tray-icon id="menu_task_manager" name="taskManager" icon="fa-tasks" type="fas" color="white"></tray-icon>
        <tray-icon id="menu_history" name="history" icon="fa-address-book" type="far" color="white"></tray-icon>
        <tray-icon id="menu_files" name="files" icon="fa-folder" type="far" color="white"></tray-icon>
    </div>
</template>

<script>

export default {
    props:{
        os: Object
    },
    methods: {
        selectMenu(el_id, name){
            if(el_id != "menu_start"){
                this.$children.forEach(function(child){
                    if(child.$el.id != el_id){
                        child.isActive = false;
                    } else {
                        child.isActive = true;
                    }
                });
                localStorage.selectedMenu = el_id;
                let sOpenedWindows = localStorage.openedWindows || "{}";
                let openedWindows = JSON.parse(sOpenedWindows);
                let id = "";
                let split_id = el_id.split('_');
                split_id.shift();
                if(split_id.length > 1){
                    split_id.forEach(function(word){
                        if(word != split_id[0]){
                            word = word.charAt(0).toUpperCase() + word.slice(1);
                        }
                        id += word;
                    });
                } else {
                    id = split_id[0];
                }
                openedWindows[id] = true;
                localStorage.openedWindows = JSON.stringify(openedWindows);
                this.$parent.updateOpenState(name);
            }
        }
    }
}
</script>
