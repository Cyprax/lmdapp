<template>
    <div class="form-group">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <button class="btn btn-red text-white" type="button" @click="$emit('deselect', {'data': dataItem.data})">
                    <font-awesome-icon icon="times"/>
                </button>
            </div>

            <input type="text" class="form-control" v-model="dataItem.label" readonly>

            <div class="input-group-append">
                <button class="btn btn-info text-white" type="button" @click="$emit('detail', dataItem.data)">
                    <font-awesome-icon icon="eye"/>
                </button>
            </div>
        </div>
    </div>
</template>


<script>
export default {
    name: 'option-bar-selection',
    props: {
        label: {
            type: String,
            default: ''
        },
        componentDetails: {
            type: String,
            default: ''
        },
        data: Object
    },
    computed: {
        dataItem() {
            return {
                label: this.label,
                componentDetails: this.componentDetails,
                data: this.data
            }
        }
    },
    methods: {
    }
}
</script>

<style scoped>
.card-body {
    height: 100%;
    width: 100%;
}

.option-bar {
    position: fixed;
    height: calc(100vh - 3.5rem);
    z-index: 10;
    top: 3.5rem;
    right: 0;
}

.option-bar-action {
    min-width: 3rem;
    padding: 0;
}
.option-bar-body {
    width: 16.5rem;
    box-shadow: 0 20px 6px 0 rgba(0, 0, 0, 0.19), 0 8px 4px 0 rgba(0, 0, 0, 0.2);
}

.row-simple {
    display: flex;
    margin: 0;
    padding: 0;
}
.col-simple {
    padding: 0;
}

.bg-transparent {
    background-color: transparent
}

.btn-red {
    color: white;
    background-color: rgb(200, 0, 0);
    border-color: rgb(200, 0, 0);
}

</style>
