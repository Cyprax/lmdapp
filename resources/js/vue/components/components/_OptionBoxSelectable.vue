<template>
    <b-list-group-item>
        <div class="form-group">
            <div class="input-group mb-3">
                <!--<div class="input-group-prepend">
                    <button class="btn btn-red text-white" type="button" @click="$emit('deselect', {'data': dataItem.data})">
                        <font-awesome-icon icon="plus"/>
                    </button>
                </div>-->

                <b-input-group class="mb-3">
                    <b-input-group-prepend is-text>
                        <b-form-radio class="mr-n2">
                            <span class="sr-only">Radio for following text input</span>
                        </b-form-radio>
                    </b-input-group-prepend>

                    <!--<b-form-input></b-form-input>-->
                    <input type="text" class="form-control" v-model="dataItem.label" readonly>

                    <b-input-group-append>
                        <button class="btn btn-info text-white" type="button" @click="$emit('detail', dataItem.data)">
                            <font-awesome-icon icon="eye"/>
                        </button>
                    </b-input-group-append>
                </b-input-group>


                <!--<div class="input-group-append">
                    <button class="btn btn-info text-white" type="button" @click="$emit('detail', dataItem.data)">
                        <font-awesome-icon icon="eye"/>
                    </button>
                </div>-->
            </div>
        </div>
    </b-list-group-item>
</template>


<script>
export default {
    name: 'option-bar-selectable',
    props: {
        item: {
            type: Object,
            default() { return {} }
        },
        data: Object
    },
    computed: {
        dataItem() {
            return this.item
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
