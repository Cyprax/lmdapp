<template>
    <div class="card bg-light px-2 py-2 mb-3">

        <div class="form-group">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="checkOptionSearch" v-model="autoSearch">
                <label class="custom-control-label" for="checkOptionSearch">Recherche automatique</label>
            </div>
        </div>

        <div class="input-group">
            <input type="text" class="form-control" placeholder="Filtrer les options"
                v-model="dataStr"
                @input="search(autoSearch)">
            <div class="input-group-append" v-show="!autoSearch">
                <button class="btn btn-primary text-white" type="button" @click="search(!autoSearch)">
                    <font-awesome-icon icon="search"/>
                </button>
            </div>
        </div>
    </div>
</template>


<script>
export default {
    name: 'search-bar',
    data() {
        return {
            autoSearch: false,
            dataStr: ""
        }
    },
    watch: {
        autoSearch: function(val) {
            this.search(val)
        }
    },
    methods: {
        search(searchable) {
            if (searchable) {
                this.$emit('search', { 'filter': this.dataStr })
            }
        }
    }
}
</script>

<style scoped>
</style>
