<template>
    <li class="nav-item" :class="{'active': dataActive}">
        <a class="nav-link text-white" :href="dataHref">
            <font-awesome-icon v-if="dataIcon" :icon="dataIcon"/>
            <span v-show="dataExpanded" class="px-1">{{ dataText }}</span>
            <span class="badge badge-dark" v-show="dataAlert">!</span>
        </a>
    </li>
</template>

<script>
export default {
    name: 'headernavitem',
    props: {
        href: {
            type: String,
            default: "#"
        },
        icon: {
            type: String,
            default: null
        },
        text: {
            type: String,
            default: null
        },
        alert: {
            type: Boolean,
            default: false
        },
        active: {
            type: Boolean,
            default: false
        },
        expanded: {
            type: Boolean,
            default: true
        }
    },
    data() {
        return {
            dataHref: this.href,
            dataIcon: this.icon,
            dataText: this.text,
            dataAlert: this.alert,
            dataActive: this.active,
            dataExpanded: this.expanded
        }
    },
    watch: {
        expanded: function() {
            this.dataExpanded = this.expanded
        }
    },
    methods: {
    }
}
</script>

<style scoped>
.nav-item {
    border-top: thick solid transparent;
    border-bottom: thick solid transparent;
}
.nav-item :hover {
    opacity: 0.75;
    /*border-radius: 0.25rem;
    border: thin dashed white;*/
}
.nav-item.active :hover {
    border: transparent
}
.nav-link :hover {
    border: transparent
}
.nav-item.active :hover {
    opacity: 1
}
.nav-item.active {
    color: rgb(255, 200, 0, 1);
    color: white;
    border-radius: 0.25rem;
    border: thin dashed rgb(255, 200, 0, 1);
    border-top: thick solid rgb(255, 200, 0, 1);
    border-bottom: thick solid rgb(255, 200, 0, 1);
}
.badge {

}
.inactive {
    border-radius: 0.25rem;
    background-color: rgb(255, 200, 0, 1);
    text-shadow: 2px 2px 4px #000000
}
</style>
