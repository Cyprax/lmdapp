<template>
    <OptionCard :title="title" :contextKey="contextMode+'-title'" :detaillable="true" :isTitle="true"/>
</template>


<script>
import OptionCard from './OptionCard'
import { getParams } from '../../utils/api'

export default {
    name: 'option-card-title',
    components: {
        OptionCard,
    },
    props: {
        items: {
            type: Array | Object,
            default() { return [{}] },
        },
        contextMode: {
            required: true,
            type: String
        },
        title: String
    },
    data() {
        return {
            pileOption: [],
            dataItems: []
        }
    },
    computed: {
        currentRoleName() {
            return this.$store.getters['auth/currentRole'].label
        },
        dataContextMode() {
            return this.contextMode
        }
    },
    mounted() {
    },
    methods: {
    }
}
</script>

<style scoped>
</style>
