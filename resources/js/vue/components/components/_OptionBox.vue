<template>
    <div>
        <div class="card">

            <nav class="navbar navbar-dark bg-light" style="font-size: large">
                <div class="nav-left">
                    Paramètrage
                </div>

                <div class="d-none d-md-inline-block ml-auto mr-0 mr-md-3 my-2 my-md-0"></div>

                <ul class="nav ml-auto ml-md-0">
                    <li class="nav-item">
                        <button class="btn btn-link" style="font-size:large" @click="loadParams">
                            <font-awesome-icon icon="redo-alt"/>
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="btn btn-link" style="font-size:large">
                            <font-awesome-icon icon="arrow-down"/>
                        </button>
                    </li>
                </ul>
            </nav>

            <div class="card-body p-0">
                <b-list-group flush>
                    <b-list-group-item
                        variant="warning"
                        v-for="(item, idx) in pileOption"
                        :key="item.label + idx"
                    >
                        <OptionBoxItemSelector :item="item" @update="updateOption"/>
                    </b-list-group-item>
                </b-list-group>
            </div>

            <div class="card-footer">
                <button type="button" class="btn btn-block btn-dark" @click="validateOptions">
                    Rechercher
                </button>
            </div>

        </div>

    </div>
</template>


<script>
import { getParams } from '../../utils/api'
import OptionBoxItemSelector from './_OptionBoxItemSelector'

export default {
    name: 'option-box',
    components: {
        OptionBoxItemSelector
    },
    props: {
        items: {
            type: Array | Object,
            default() { return [{}] },
        },
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
            return this.$store.getters['auth/currentRole']ntRole'].label
        }
    },
    mounted() {
        this.loadParams()
    },
    methods: {
        validateOptions() {
            this.$emit('update', this.pileOption)
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
        },
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
            //Reinitialiser les seletors

            if ( !this.currentRoleName ) {
                return
            }
            getParams('eval', this.currentRoleName ).then( ({data}) => {
                //[ {label: "classe", descript: "Classe", next: "semesters"}, {label: "semester", descript: "Semestre", next: "ues"}, ... ]
                let meta = data.meta
                this.pileOption = []
                meta.forEach( (objMeta) => {
                    this.pileOption.push( {
                        label: objMeta.label,
                        descript: objMeta.descript,
                        next: objMeta.next,
                        datas: [],
                        currentValue: null
                    } )
                });
                this.setOptionsMode()

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
.card {
    border-style: solid;
    border-left-width: medium;
    border-left-color: darkslategrey;
}
.navbar {
    border: 1px solid darkslategrey;
    border-left: 0;
}
.card-body {
    border: 1px solid darkslategrey;
    border-left: 0;
    border-top: 0;
}
.card-footer {
    border: 1px solid darkslategrey;
    border-top: 0;
    border-left: 0;
}
</style>
