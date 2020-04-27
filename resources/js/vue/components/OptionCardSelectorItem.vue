<template>
    <b-form-group
        id="fieldset-horizontal"
        label-size="sm"
        label-cols="3"
        label-cols-sm="3"
        label-cols-lg="2"
        :label="dataItem.descript"
        class="my-0 form-group-radio mb-2"
    >

        <b-input-group size="sm">
            <b-form-select
                :disabled="controlDisabled"
                v-model="indexSelected"
                :options="dataOptions"
                size="sm"
                text-field="label"
                value-field="id"
                @change="/*update*/"
            ></b-form-select>
            <b-input-group-append>
                <button class="btn btn-outline-info" :disabled="controlDisabled">
                    <font-awesome-icon icon="eye"/>
                </button>
            </b-input-group-append>
        </b-input-group>

    </b-form-group>
</template>

<script>
import { isUndefined } from 'util'
export default {
    name: 'option-card-selector-item',
    components: {
    },
    props: {
        item: {
            type: Array | Object,
            default() { return [{}] },
        },
        currentDisplayMode: String,
        if: Boolean
    },
    data() {
        return {
            indexSelected: this.initSelected(),
            displayable: this.displayability(),
        }
    },
    watch: {
        indexSelected: function(indexSelected) {
            this.$emit('update', {
                label: this.dataItem.label,
                value: _.find( this.dataItem.datas, function(obj) {
                    return obj.id == indexSelected
                } )
            })
        },
        currentDisplayMode: function() {
            this.displayable = this.displayability()
        }
    },
    computed: {
        dataItem() {
            return this.item;
        },
        hasNoSelectedIndex() {
            /*console.log('hereeee')
            let sIndex = this.selectedIndex
            let obj = _.find(this.dataItem, function(obj) { return obj.id == sIndex })
            return _.isEmpty(obj)
            if ( _.isEmpty(obj) ) {
                console.log('here')
                this.selectedIndex = 'null'
            }*/return null
        },
        controlDisabled() {
            return this.item.datas.length == 0
        },
        dataOptions() {
            let options = this.item.datas
            if (this.item.datas.length == 0) {
                options = [ {id: 'null', label: 'Aucun élément disponible', disabled: true} ]
            } else {
                if ( this.item.mode == 'select' ) {
                    options = [
                        ...options,
                        { id: 'null', label: 'Veuillez sélectionner un élément', disabled: true },
                    ]
                }
            }
            return options
        },
    },
    methods: {
        /*update(payload){
            this.$emit('update', {
                label: this.dataItem.label,
                value: _.find( this.dataItem.datas, function(obj) {
                    return obj.id == payload
                } )
            })
        },*/
        initSelected() {
            return !isUndefined(this.item.defaultIndex) ? this.item.defaultIndex : 'null'
            /*if (this.item.mode == 'select') {
                return this.item.datas.length == 0 ? 'null' : 'undefined'
            } else {
                return null
            }*/
        },
        displayability() {
            return true
            /*return (
                this.dataItem.displayIf == '*' ||
                this.dataItem.displayIf.contains( this.currentDisplayMode )
            )*/
        },
    }
}

</script>

<style>
fieldset.form-group-radio div.form-row div.col{
    display: flex;
    align-items: center;
}
fieldset.form-group-radio div.form-row div.col div[role=radiogroup]{
    width: 100%;
}
</style>
