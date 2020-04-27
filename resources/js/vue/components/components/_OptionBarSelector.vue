<template>
    <div>
        <div class="alert alert-info mb-3" role="alert">{{ currentLabel }}</div>

        <GenericSearchBar @search="dataFilter = $event.filter"/>

        <!--<OptionBarSelectable
            v-for="item in (dataItems | search )" :key="item.id"
            :item="item"
            @select="$emit('select', {'label': item.meta.currentOption, 'data': item})"
        />-->

        <OptionBarSelectable
            v-for="item in dataItems" :key="item.id"
            :item="item"
            @select="$emit('select', {'label': item.meta.currentOption, 'id': item.id, 'data': item})"
        />
    </div>
</template>


<script>
import GenericSearchBar from './_GenericSearchBar'
import OptionBarSelectable from './_OptionBarSelectable'

export default {
    name: 'option-bar-selector',
    components: {
        GenericSearchBar,
        OptionBarSelectable
    },
    props: {
        items: Array
    },
    data() {
        return {
            dataFilter: ""
        }
    },
    computed: {
        dataItems() {
            return this.items
        },
        currentLabel() {
            return this.items.length ? this.items[0].meta.currentText : null
        }
    },
    methods: {
    },
    filters: {
        search(value) {
            /////////////////////////////
            return
        }
    }
}
</script>

<style scoped>
</style>
