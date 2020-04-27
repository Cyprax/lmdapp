<template>
    <OptionCard :title="title" :contextKey="contextMode+'-table'"
        :reloadable="true" :minifiable="true" :busy="busy"
        @reload="$emit('reload', $event)"
    >
        <template v-slot:default>
            <GenericTable :columns="columns" :rows="rows" :meta="meta"/>
        </template>
    </OptionCard>
</template>


<script>
import OptionCard from './OptionCard'
import GenericTable from './GenericTable'
import { getParams } from '../../utils/api'

export default {
    name: 'option-card-table',
    components: {
        OptionCard,
        GenericTable
    },
    props: {
        columns: Array,
        rows: Array,
        meta: Object,
        title: String,
        contextMode: {
            required: true,
            type: String
        },
        busy: Boolean
    }
}
</script>

<style scoped>
</style>
