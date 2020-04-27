<template>
    <OptionCard title="Plus d'options" :minifiable="true" :contextKey="dataContextMode+'-plus'">
        <template v-slot:default>
            <div class="card-body">Aucune option disponible</div>
        </template>
        <template v-slot:footer>
            <button class="btn btn-block btn-info">Valider les moyennes</button>
        </template>
    </OptionCard>
</template>


<script>
import OptionCard from './OptionCard'
import { getParams } from '../../utils/api'

export default {
    name: 'option-card-plus',
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
