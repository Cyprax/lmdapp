<template>
    <div>
        <OptionCardSelectorItem :item="objParamMode" @update="updateParamMode($event)"/>
        <template v-for="(item, idx) in pileOption">
                <OptionCardSelectorItem :item="item" :key="item.label + idx"
                    :currentDisplayMode="dataCurrentDisplayObject.value.id"
                    :if="paramModality(item.if)"
                    v-show="paramModality(item.if)"
                    @update="updateOption"
                />
        </template>
    </div>
</template>


<script>
import OptionCard from './OptionCard'
import OptionCardSelectorItem from './OptionCardSelectorItem'
import { getParams } from '../../utils/api'

export default {
    name: 'option-card-parameter-eval',
    components: {
        OptionCard,
        OptionCardSelectorItem
    },
    props: {
        pileOption: {
            type: Array | Object,
            default() { return [{}] },
        },
    },
    data() {
        return {
            currentDisplayObject: { label: 'display', value: { id: 'BY_SEM', label: 'Planche semestrielle' } },
            objParamMode: {
                currentValue: { id: 'BY_SEM', label: 'Planche semestrielle' },
                descript: "Mode d'affichage",
                label: "display",
                mode: "select",
                next: null,
                defaultIndex: 'BY_SEM',
                datas: [
                    { id: 'BY_SEM', label: 'Planche semestrielle' },
                    { id: 'BY_UE', label: 'Afficher par UE' },
                    { id: 'BY_ECUE', label: 'Afficher par ECUE' }
                ],
                displayIf: '*'
            }
        }
    },
    computed: {
        dataPileOption() {
            return this.pileOption
        },
        dataCurrentDisplayObject() {
            return this.currentDisplayObject
        },
    },
    methods: {
        /*isItemDisplayable(item) {
            let rule = item.displayabilityRule ? item.displayabilityRule : '*'
            let currentRuleValue = (
                    this.dataCurrentDisplayObject &&
                    this.dataCurrentDisplayObject.value &&
                    this.dataCurrentDisplayObject.value.id
                ) ? this.dataCurrentDisplayObject.value.id : null

            let result = null
            if (rule == '*') result = true
            else if (currentRuleValue) result = false
            else result = rule.includes(currentRuleValue)
            return result
        },*/
        paramModality(ifClause) {
            if (ifClause == '*') return true
            else {
                //console.log(ifClause)
                let currentId = this.currentDisplayObject.value.id
                return ifClause.includes(currentId)
            }
        },
        updateParamMode(payload) { //{ value : {id : 'BY_*', ...}, ... }
            this.currentDisplayObject = payload
            let newParamMode = payload.value.id
            this.$emit('updateParamMode', newParamMode)
        },
        updateOption(payload) { //payload = { 'label': #, 'data': { id: #, label: # } }
            let indexOption = _.findIndex(this.pileOption, function(obj) {
                return obj.label == payload.label
            })

            let objOption = this.pileOption[ indexOption ]
            objOption.currentValue = payload.value

            let nextIndex = indexOption + 1;
            let isFirstNextObj = true
            while ( this.pileOption[nextIndex] ) {
                this.pileOption[nextIndex].currentValue = {};
                if ( isFirstNextObj ) {
                    this.pileOption[nextIndex].datas = objOption.currentValue[objOption.next] //???
                } else {
                    this.pileOption[nextIndex].datas = []
                }
                isFirstNextObj = false
                nextIndex++;
            }
            /*emit('updateParameters', {
                'options': this.pileOption,
                'if': this.dataCurrentDisplayObject.value.id
            })*/
        }
    }
}
</script>

<style scoped>
</style>
