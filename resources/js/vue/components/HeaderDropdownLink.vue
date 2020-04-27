<template>
    <router-link :tag="dataTag" :to="{ name: dataName, params: dataParams }" class="dropdown-item">
        <font-awesome-icon :icon="dataIcon"/>
        {{ dataTitle }}
    </router-link>
</template>

<script>

export default {
    props: {
        name: String,
        title: String,
        icon: String,
        tag: {
            type: String,
            default: "a"
        },
        params: {
            type: Object,
            default: function() { return {} }
        }
    },
    data: function() {
        return {
            dataName: this.name,
            dataParams: this.params,
            dataTitle: this.title,
            dataIcon: this.icon,
            dataTag: this.tag
        }
    },
    methods: {
    }
}
</script>
