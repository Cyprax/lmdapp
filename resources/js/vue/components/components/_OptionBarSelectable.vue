<template>
    <div class="form-group">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <button class="btn btn-success text-white" type="button" @click="$emit('select')">
                    <font-awesome-icon icon="plus"/>
                </button>
            </div>

            <input type="text" class="form-control" v-model="dataItem.label" readonly>

            <div class="input-group-append">
                <button class="btn btn-info text-white" type="button" @click="$emit('detail', dataItem)">
                    <font-awesome-icon icon="eye"/>
                </button>
            </div>
        </div>
    </div>
</template>


<script>
export default {
    name: 'option-bar-selectable',
    props: {
        item: Object
    },
    computed: {
        dataItem() {
            return this.item
        }
    }
}
</script>

<style scoped>
.btn-red {
    color: white;
    background-color: rgb(200, 0, 0);
    border-color: rgb(200, 0, 0);
}

</style>
