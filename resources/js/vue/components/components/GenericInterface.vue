<template>
    <div class="app-generic">

        <!--<div class="mb-3">
            <OptionBox
                v-if="dataHasOption"
                @update="$emit('update', $event)"/>
        </div>-->

        <div class="" :class="{ 'has-option': dataHasOption }">
            <slot :data="dataApi"></slot>
        </div>

    </div>
</template>

<script>
import OptionBox from './_OptionBox'
import { getParams } from '../../utils/api'

export default {
    name: "generic",

    components: {
        OptionBox,
    },
    props: {
        hasOption: {
            type: Boolean,
            default: true
        },
        componentName: {
            type: String,
            default: '' //required: true
        },
    },
    data() {
        return {
            dataHasOption: this.hasOption,
            dataComponentName: this.componentName,
            dataApi: {},
        }
    },
    methods: {
        /*updateApiData(pileDatas) { //dataSelected
            let roleName = this.currentRoleName
            let component = this.dataComponentName

            this.dataApi = pileDatas
            /* = getDataEvaluation(this.currentRole.label, this.currentEval.id).then( (response) => {
                this.dataApi = response.data
            })*** /
        },*/
    }
}
</script>

<style scoped>
.container-fluid {
    width: 100%;
}

</style>
