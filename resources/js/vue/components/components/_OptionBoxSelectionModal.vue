<template>
    <b-modal :id="dataId" ref="modal" :title="title"
        @show="resetModal" @hidden="resetModal" @ok="handleOk">

        <form ref="form" @submit.stop.prevent="handleSubmit">
            <b-form-group>
                <b-list-group flush>
                    <!--<OptionBoxSelectable v-for="(item, idx) in dataItems" :key="idx" :item="item"/>-->
                        <!--<OptionBoxSelectable :item="{'label': '1'}"/>-->

                    <b-list-group-item v-for="(item, idx) in currentModalDatas.data" :key="idx">
                        <input type="radio" :id="'radio-'+item.id" :value="item" v-model="selected">
                        <label :for="'radio-'+item.id">{{item.label}}</label>
                        <!--<b-form-radio v-model="selected" name="exple-radios">
                            {{ item.label }}
                        </b-form-radio>-->
                    </b-list-group-item>
                </b-list-group>

            </b-form-group>

            <!--<b-form-group
            :state="nameState"
            label="Name"
            label-for="name-input"
            invalid-feedback="Name is required"
            >
            <b-form-input
                id="name-input"
                v-model="name"
                :state="nameState"
                required
            ></b-form-input>
            </b-form-group>-->
        </form>

    </b-modal>
</template>

<script>
import { getBeforeEvaluation } from '../../utils/api'
import OptionBoxSelection from './_OptionBarSelection'
import OptionBoxSelectable from './_OptionBarSelectable'

export default {
    name: 'option-box-selection-modal',
    components: {
        OptionBoxSelectable,
    },
    props: {
        id: String,
        items: {
            type: Array | Object,
            default() { return [{}] },
        },
    },
    data() {
        return {
            title: "Sélection - Semestre",
            selected: null,
            currentOption: '',
            nextOption: ''
        }
    },
    computed: {
        dataId() {
            return this.id
        },
        dataMode() {
            if ( ! _.values(this.item.data).length ) {
                return 'undetermined'
            } else if ( !this.item.id ) {
                return 'unselected'
            } else {
                return 'selected'
            }
        },
        currentModalDatas() {
            return this.$store.getters['datas/currentModalDatas'];
        }
    },
    mounted() {
    },
    methods: {
        resetModal() {
            this.selected = null
        },
        handleOk(bvModalEvt) {
            // Prevent modal from closing
            bvModalEvt.preventDefault()
            // Trigger submit handler
            this.handleSubmit()
            this.$emit('select', {
                'label': this.currentModalDatas.label,
                'selected': this.selected,
                'next': null
            })
        },
        handleSubmit() {
            // Exit when the form isn't valid
            /*if (!this.checkFormValidity()) {
                return
            }*/
            // Push the name to submitted names
            //this.$emit.push(this.selected)
            // Hide the modal manually
            this.$nextTick(() => {
                this.$refs.modal.hide()
            })
        }
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
