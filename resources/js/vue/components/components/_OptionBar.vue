<template>
    <div class="option-bar row row-simple justify-content-end" :class="{'option-bar-expand': dataState}">

        <div class="col col-simple" :style="{'opacity': dataState ? 1 : 0.5}">
            <div class="option-bar-action card-header rounded-0 bg-primary text-white" @click="$emit('toggleState')">
                <button class="btn btn-link text-white" style="font-size:x-large">
                    <font-awesome-icon :icon="dataState ? 'chevron-circle-right' : 'chevron-circle-left'"/>
                </button>
            </div>
            <div class="option-bar-action card-header rounded-0 bg-primary text-white overflow-auto">
                <button class="btn btn-link text-white" style="font-size:x-large">
                    <font-awesome-icon icon="redo-alt"/>
                </button>
            </div>
            <div class="option-bar-action card-header rounded-0 bg-primary text-white">
                <button class="btn btn-link text-white" style="font-size:x-large">
                    <font-awesome-icon icon="reply-all"/>
                </button>
            </div>
        </div>

        <div class="option-bar-body rounded-0 bg-light overflow-auto" v-show="dataState">

            <div class="card-header bg-styled">
                <div class="alert alert-warning" role="alert" v-show="pileOption.length == 0">
                    Aucune option sélectionnée
                </div>
                <div class="alert alert-info" role="alert" v-show="pileOption.length != 0">
                    Element(s) sélectionné(s)
                </div>

                <OptionBarSelection
                    v-for="(item, idx) in pileOption"
                    :key="idx"
                    :label="item.data.label"
                    :data="item.data"
                    @deselect="depiler(idx)"
                />
            </div>

            <div class="card-body">
                <div class="alert alert-warning" v-if="!dataItemsFiltered || !dataItemsFiltered.length">
                    Aucune option disponible
                </div>

                <template v-else>
                    <OptionBarSelector :items="dataItemsFiltered" @select="empiler"/>
                </template>
            </div>

        </div>

    </div>
</template>


<script>
import OptionBarSelection from './_OptionBarSelection'
import OptionBarSelector from './_OptionBarSelector'
import { getBeforeEvaluation } from '../../utils/api'

export default {
    name: 'option-bar',
    components: {
        OptionBarSelection,
        OptionBarSelector
    },
    props: {
        componentName: String,
        state: Boolean,
        items: {
            type: Array | Object,
            default() { return [{}] },
        },
    },
    data() {
        return {
            pileOption: [],
            dataItems: this.getItems()//this.items
        }
    },
    watch: {
        pileOption: function(val) {
            this.$emit('change', { 'data' : val })
        },
        currentRoleName: function(val) {
            this.getItems()
        }
    },
    computed: {
        dataState() {
            return this.state
        },
        dataItemsFiltered() {
            if (this.pileOption.length == 0) {
                return this.dataItems
            } else {
                let lastIndex = this.pileOption.length - 1
                let lastItem = this.pileOption[ lastIndex ].data
                let nextOption = lastItem.meta.nextOption
                return nextOption ? lastItem[ "" + nextOption ] : null
            }
        },
        currentRoleName() {
            return this.$store.getters['auth/currentRole'].label
        }
    },
    mounted() {
        this.getItems()
    },
    methods: {
        empiler(event) { //event / payload = {label: '', data: ''}
            this.pileOption.push(event) //push('label': event.label, 'data': event.data)
        },
        depiler(untilIndex) {
            this.pileOption = this.pileOption.slice(0, untilIndex)
        },
        getItems() {
            if ( !this.currentRoleName ) {
                return
            }
            getBeforeEvaluation( this.currentRoleName ).then( (response) => {
                this.dataItems = response.data.data
            }).catch( (error) => {
                console.log('error: ', error)
            })
        }
    }
}
</script>

<style scoped>
.card-header {
    padding: 1rem 0.5rem 0.5rem
}
.card-body {
    padding: 1.5rem 0.5rem;
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
    max-width: 25rem;
    height: calc(100vh - 3.5rem);
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
