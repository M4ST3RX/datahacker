<template>
    <div id="main_container" style="color: white; position: absolute;">
        <slot name="operating_system" :os="os"></slot>
    </div>
</template>

<script>
export default {
    props: {
        os: {
            type: Object,
            required: true
        }
    },
    mounted(){
        this.updateAllOpenState();
    },
    methods: {
        updateAllOpenState(){
            if(localStorage.openedWindows !== undefined){
                let openedWindows = JSON.parse(localStorage.openedWindows) || {};
                this.$children.forEach(function(child){
                    if(openedWindows[child.name]){
                        child.isOpen = true;
                    }
                });
            }
        },
        updateOpenState(name){
            if(localStorage.openedWindows !== undefined){
                if(JSON.parse(localStorage.openedWindows)[name]){
                    this.$children.forEach(function(child){
                        if(child.name == name){
                            child.isOpen = true;
                        }
                    });
                }
            }
        },
        getOpenState(name){
            this.$children.forEach(function(child){
                if(child.name == name){
                    return child.isOpen;
                }
            });
            return false;
        },
        closeWindow(name){
            let openedWindows = JSON.parse(localStorage.openedWindows) || {};
            openedWindows[name] = false;
            localStorage.openedWindows = JSON.stringify(openedWindows);
            this.$children.forEach(function(child){
                if(child.name == name){
                    child.isOpen = false;
                }
            });
        }
    }
}
</script>
