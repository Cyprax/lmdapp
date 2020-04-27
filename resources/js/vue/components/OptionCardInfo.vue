<template>
    <OptionCard title="Informations utiles" :minifiable="true" :contextKey="dataContextMode+'-info'">
        <template v-slot:default>
            <div class="card-body">Aucune option disponible</div>
        </template>
    </OptionCard>
</template>


<script>
import OptionCard from './OptionCard'
import { getParams } from '../../utils/api'

export default {
    name: 'option-card-info',
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
        }
    },
    data() {
        return {
            pileOption: [],
            dataItems: []
        }
    },
    watch: {
        currentRoleName: function(val) {
            this.loadParams()
        }
    },
    computed: {
        currentRoleName() {
            return this.$store.getters['auth/currentRole'].label
        },
        dataContextMode() {
            return this.contextMode
        }
    }
}
</script>

<style scoped>
</style>
