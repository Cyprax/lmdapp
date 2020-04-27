<template>
    <b-form-group
        id="fieldset-horizontal"
        label-size="sm"
        label-cols="4"
        label-cols-sm="3"
        label-cols-lg="3"
        :label="dataItem.descript"
        class="my-0 form-group-radio"
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

        <!--<b-form-radio-group v-if="dataItem.mode == 'radio'"
            v-model="indexSelected"
            :options="dataOptions"
            text-field="label"
            value-field="id"
            size="sm"
            :name="'radio-item-data-' + dataItem.label"
            @change="/*update*/"
        ></b-form-radio-group>

        <b-form-select v-else-if="dataItem.mode == 'select'"
            :disabled="controlDisabled"
            v-model="indexSelected"
            :options="dataOptions"
            size="sm"
            text-field="label"
            value-field="id"
            @change="/*update*/"
        ></b-form-select>

        <template v-else></template>

        <button class="btn btn-link">
            <font-awesome-icon icon="eye"/>
        </button>-->

    </b-form-group>
</template>

<script>
export default {
    name: 'option-box-item-selector',
    components: {
    },
    props: {
        item: {
            type: Array | Object,
            default() { return [{}] },
        },
    },
    data() {
        return {
            indexSelected: this.initSelected()
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
            return 'null'
            /*if (this.item.mode == 'select') {
                return this.item.datas.length == 0 ? 'null' : 'undefined'
            } else {
                return null
            }*/
        }
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
