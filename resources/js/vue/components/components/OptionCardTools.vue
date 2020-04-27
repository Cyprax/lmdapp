<template>
    <OptionCard title="Outils de supervision" :minifiable="true" :contextKey="dataContextMode+'-super'">
        <template v-slot:default>

            <OptionCardToolsBodyEval v-if="dataContextMode == 'eval'"
                :paramsSelector="paramsSelector" @update="paramsTools = $event"
            />

            <!--
                <OptionCardToolsBodyPres v-if="dataContextMode == 'pres'"/>
                <OptionCardToolsBodyPres v-if="dataContextMode == 'text'"/>
                <OptionCardToolsBodyText v-if="dataContextMode == '...'"/>
            -->

        </template>
    </OptionCard>
</template>


<script>
import OptionCard from './OptionCard'
import OptionCardToolsBodyEval from './OptionCardToolsBodyEval'

export default {
    name: 'option-card-tools',
    components: {
        OptionCard,
        OptionCardToolsBodyEval
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
        paramsSelector: Array | Object
    },
    data() {
        return {
            paramsTools: []
        }
    },
    watch: {

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
        this.loadParams()
    },
    methods: {
    }
}
</script>

<style scoped>
</style>
