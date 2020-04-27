<template>
    <div class="form-group">
        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="form-control" :checked="checked" @change="$emit('change', $event.target.checked)">
            <label class="custom-control-label">Recherche automatique</label>
        </div>
    </div>
</template>


<script>
export default {
    name: 'search-bar-check-box',
    model: {
        prop: 'checked',
        event: 'change'
    },
    props: {
        checked: Boolean
    },
}
</script>

<style scoped>
</style>
