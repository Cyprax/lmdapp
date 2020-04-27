<template>
    <button class="btn" :class="classObject" type="button" @click="$emit('click', $event)">
        <font-awesome-icon :icon="icon"/>
    </button>
</template>


<script>
export default {
    name: 'app-button-icon',
    props: {
        icon: String,
        textVariant: {
            type: String,
            default: 'white'
        },
        bgVariant: {
            type: String,
            default: 'primary'
        }
    },
    computed: {
        classObject() {
            let txtVariant = 'text-' + this.textVariant
            let bgVariant = 'btn-' + this.bgVariant
            return {
                txtVariant: true,
                bgVariant: true,
            }
        }
    },
    methods: {
    }
}
</script>

<style scoped>
.bg-transparent {
    background-color: transparent
}

.btn-red {
    color: white;
    background-color: rgb(200, 0, 0);
    border-color: rgb(200, 0, 0);
}

</style>
