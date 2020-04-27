<template>
    <OptionCard title="Paramétrage" :contextKey="dataContextMode+'-params'"
        :reloadable="true" :minifiable="true" :submitable="true"
        @reload="loadParams" @submit="validateOptions"
    >
        <template v-slot:default>
            <div class="px-2 mt-2">
                <OptionCardParameterEval v-if="dataContextMode == 'eval'"
                    :pileOption="dataPileOption"
                    @updateParamMode="paramMode = $event"
                />
            </div>
        </template>
    </OptionCard>
</template>


<script>
import OptionCard from './OptionCard'
import OptionCardParameterEval from './OptionCardParameterEval'
import { getParams } from '../../utils/api'

export default {
    name: 'option-card-parameter',
    components: {
        OptionCard,
        OptionCardParameterEval
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
            dataItems: [],
            paramMode: 'BY_SEM'
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
        },
        dataPileOption() {
            return this.pileOption
        }
    },
    mounted() {
        this.loadParams()
    },
    methods: {
        validateOptions() {
            this.$emit('update', {
                'params': this.pileOption,
                'paramMode': this.paramMode
            })
            //this.$emit('update', this.pileOption)
        },
        /*updateOption(payload) { //payload = { 'label': #, 'data': { id: #, label: # } }
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
        },*/
        setOptionsMode() {
            this.pileOption.forEach( (objOption) => {
                if (objOption.label == 'semester' && objOption.datas.length <= 2 ) {
                    objOption.mode = 'radio'
                } else {
                    objOption.mode = 'select'
                }
            })
        },
        loadParams() {
            //Reinitialiser les selectors
            if ( !this.currentRoleName ) {
                return
            }
            getParams(this.dataContextMode, this.currentRoleName ).then( ({data}) => {
                //[ {label: "classe", descript: "Classe", next: "semesters"}, {label: "semester", descript: "Semestre", next: "ues"}, ... ]
                let meta = data.meta
                this.pileOption = []
                meta.forEach( (objMeta) => {
                    this.pileOption.push( {
                        label: objMeta.label,
                        descript: objMeta.descript,
                        next: objMeta.next,
                        if: objMeta.if,
                        datas: [],
                        currentValue: null
                    } )
                });
                this.setOptionsMode() //??? Is it still really important?

                this.dataItems = data.data
                if (this.pileOption.length) {
                    let firstOption = _.find(this.pileOption, function(elmt) {
                        return elmt.label == meta[0].label
                    })
                    firstOption.datas = data.data
                }
            }).catch( (error) => {
                console.log('error: ', error)
            })
        }
    }
}
</script>

<style scoped>
</style>
