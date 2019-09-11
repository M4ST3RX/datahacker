<template>
    <div :id="id" :name="name" @click="selectMenu($event)" class="main-menu" :class="{ 'menu-selected': isActive }">
        <i :class="[icon, type]" :style="{color: color}" class="fa-lg" style="padding: 10px;"></i>
    </div>
</template>

<script>
export default {
    props: ['id', 'icon', 'type', 'color', 'name'],
    data() {
        return {
            isActive: false
        }
    },
    mounted(){
        if(this.id == localStorage.selectedMenu) {
            this.isActive = true;
        }
    },
    methods: {
        selectMenu(event){
            this.$parent.selectMenu(event.currentTarget.id, this.name);
        }
    }
}
</script>
