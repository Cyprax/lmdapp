<template>
    <div id="overlayer" @click="closeSideBar"></div>
</template>

<script>
export default {
    name: 'app-overlayer',
    methods: {
        closeSideBar() {
            this.$store.commit("window/updateWinOverlay", {
                winOverlayState: false
            })
        }
    }
}
</script>

<style>
</style>
