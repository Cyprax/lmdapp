<template>
</template>

<script>
export default {
    name: "",
    components: {
    },
    props: {
    },
    data() {
        return {
        }
    },
    computed: {
        currentRoleName() {
            return this.$store.getters['auth/currentRole'].label
        },
    },
    mounted() {
    },
    methods: {
    }
}
</script>

<style scoped>
</style>
