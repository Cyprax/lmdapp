<template>
    <b-list-group-item :to="dataTo" replace exact-active-class="active" v-if="!notExact">
        {{ dataTitle }}
        <span class="float-right">
            <font-awesome-icon :icon="dataIcon"/>
        </span>
    </b-list-group-item>

    <b-list-group-item :to="dataTo" replace active-class="active" v-else>
        {{ dataTitle }}
        <span class="float-right">
            <font-awesome-icon :icon="dataIcon"/>
        </span>
    </b-list-group-item>
</template>

<script>
export default {
    props: {
        icon: String,
        title: String,
        to: String,
        params: {
            type: Object,
            default: function() { return {} }
        },
        notExact: {
            type: Boolean,
            default: false
        }
    },
    data: function() {
        return {
            dataIcon: this.icon,
            dataTitle: this.title,
            dataParams: this.params,
            dataTo: this.to,
        }
    }
}
</script>

<style>
.list-group-item.active {
    font-weight: bold;
    border-radius: 0;
    border-left: .25rem solid rgb(255, 200, 0);
    border: 0px;
    border-left: .25rem solid goldenrod;
    background-color: #009688;
}
</style>
