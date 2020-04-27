<template></template>

<script>
import { getCurrentWinState } from "../../utils/window"

export default {
    created() {
        window.addEventListener('resize', this.hasReachedBreakpoint);
        this.hasReachedBreakpoint()
    },
    computed: {
        winState() {
            return this.$store.getters['window/winState']
        }
    },
    methods: {
        resizeWindow(winState) {
            this.$store.commit("window/resizeWindow", winState)
        },
        hasReachedBreakpoint() {
            let winState = getCurrentWinState()
            if (this.winState != winState) {
                this.resizeWindow(winState)
            }
        }
    },
    beforeDestroy() {
        window.removeEventListener('resize', this.hasReachedBreakpoint);
    }

}
</script>
