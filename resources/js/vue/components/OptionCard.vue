<template>
    <div class="card table-card mb-4">
        <nav class="navbar navbar-dark bg-texture" style="font-size: large">
            <div class="nav-left">
                <h3 v-if="isTitle">{{title}}</h3>
                <template v-else>{{title}}</template>
            </div>

            <div class="d-none d-md-inline-block ml-auto mr-0 mr-md-3 my-2 my-md-0"></div>

            <ul class="nav ml-auto ml-md-0">
                <li class="nav-item" v-if="submitable">
                    <b-button variant="link" size="sm" style="font-size:large" @click="$emit('submit')">
                        <font-awesome-icon icon="check"/>
                    </b-button>
                </li>

                <template v-if="reloadable">
                    <li class="nav-item" v-if="!busy">
                        <b-button variant="link" size="sm" style="font-size:large" @click="$emit('reload')">
                            <font-awesome-icon icon="redo-alt"/>
                        </b-button>
                    </li>

                    <div class="spinner-grow text-primary" role="status" v-else>
                        <span class="sr-only">Loading...</span>
                    </div>
                </template>

                <li class="nav-item" v-if="minifiable">
                    <b-button variant="link" size="sm" style="font-size:large"
                        @click="inverseMinify" v-b-toggle="'option-collapse-' + dataKey">
                        <font-awesome-icon :icon="dataIconMinified"/>
                    </b-button>
                </li>

                <li class="nav-item" v-if="detaillable">
                    <b-button variant="link" size="sm" style="font-size:large" @click="$emit('detail')">
                        <font-awesome-icon icon="eye"/>
                    </b-button>
                </li>
            </ul>
        </nav>

        <b-collapse visible :id="'option-collapse-' + dataKey">
            <slot></slot>
        </b-collapse>
    </div>
</template>

<script>
export default {
    name: "option-card",

    props: {
        title: String,
        contextKey: {
            required: true,
            type: String
        },
        reloadable: Boolean,
        minifiable: Boolean,
        submitable: Boolean,
        detaillable: Boolean,
        busy: Boolean,
        isTitle: Boolean
    },
    data() {
        return {
            minified: false,
            iconMinified: 'minus',
        }
    },
    computed: {
        dataKey() {
            return this.contextKey + this.key
        },
        dataMinified() {
            return this.minified
        },
        dataIconMinified() {
            return this.iconMinified
        },
        dataTitle() {
            return this.title
        },
    },
    methods: {
        inverseMinify() {
            this.minified = !this.minified
            this.iconMinified = this.minified ? 'plus' : 'minus'
        },
    }
}
</script>

<style scoped>
.card .card-header .card-obj {
    float: right;
}
.card .card-header .card-header-right {
    border-radius: 0 0 0 7px;
    right: 10px;
    top: 18px;
    display: inline-block;
    padding: 7px 0;
    position: absolute;
}
.card .card-header .card-header-right .card-option {
    transition: .3s ease-in-out;
    margin-bottom: 0;
}
.card .card-header .card-header-right li {
    display: inline-block;
}
.card .card-header .card-header-right svg {
    margin: 0 8px;
    cursor: pointer;
    font-size: 16px;
    color: #919aa3;
    line-height: 20px;
}

.bg-nav-gold {
    background-color:goldenrod;
}

.navbar {
    min-height: 60px;
}

.card {
    border-style: solid;
    border-left-width: medium;
    border-left-color: teal;
}
.navbar {
    border: 1px solid darkslategrey;
    border-left: 0;
}
.card-body {
    border: 1px solid darkslategrey;
    border-left: 0;
    border-top: 0;
}
.card-footer {
    border: 1px solid darkslategrey;
    border-top: 0;
    border-left: 0;
}

</style>
