<template>
    <div>
        <div class="card m-3">
            <div class="card-header">
                {{ dataItem.label }}
            </div>

            <div class="card-body card-centered">
                <template v-if="dataMode == 'undetermined'">
                    <div class="text-primary" style="font-size: 70px">
                        <font-awesome-icon icon="times"/>
                    </div>
                </template>

                <template v-if="dataMode == 'unselected'">
                    <div class="text-success">
                        <b-button variant="success" style="font-size: x-large"
                            v-b-modal.modal-selector @click="loadModal">
                            <font-awesome-icon icon="plus"/>
                            SELECTIONNER
                        </b-button>
                    </div>
                </template>

                <template v-if="dataMode == 'selected'">
                    <div> {{ dataItem.data.label }} </div>
                </template>
            </div>

            <div class="card-footer" v-if="dataMode == 'selected'">
                <div class="row justify-content-center">
                    <button class="btn btn-outline-info mx-1">
                        <font-awesome-icon icon="eye"/>
                    </button>
                    <button class="btn btn-outline-secondary mx-1">
                        <font-awesome-icon icon="pen"/>
                    </button>
                    <button class="btn btn-outline-primary mx-1">
                        <font-awesome-icon icon="times"/>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
import { getBeforeEvaluation } from '../../utils/api'
import OptionBoxSelection from './_OptionBarSelection'

export default {
    name: 'option-box-selector',
    components: {
        OptionBoxSelection,
        //OptionBoxSelector
    },
    props: {
        item: {
            type: Array | Object,
            default() { return [{}] },
        },
    },
    computed: {
        dataMode() {
            if ( ! _.values(this.item.data).length ) {
                return 'undetermined'
            } else if ( !this.item.id ) {
                return 'unselected'
            } else {
                return 'selected'
            }
        },
        dataItem() {
            return this.item;
        },
    },
    mounted() {
    },
    methods: {
        loadModal() {
            this.$store.commit('datas/updateCurrentModalDatas', {
                label: this.dataItem.label,
                data: this.dataItem.data
            });
            //openModal
        },
        /*empiler(event) { //event / payload = {label: '', data: ''}
            this.pileOption.push(event) //push('label': event.label, 'data': event.data)
        },
        depiler(untilIndex) {
            this.pileOption = this.pileOption.slice(0, untilIndex)
        },*/
    }
}
</script>

<style scoped>
.card-header {
    text-align: center;
}
.card {
    min-height: 200px;
}
.card-centered {
    justify-content: center;
    display: flex;
    align-items: center;
    text-align: center;
}
</style>
