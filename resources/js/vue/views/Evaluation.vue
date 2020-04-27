<template>
    <div class="container-fluid">

        <OptionCardTitle title="Menu des évaluations" :contextMode="contextMode"/>

        <div class="row justify-content-center">
            <div class="col-12">
                <OptionCardParameter :contextMode="contextMode" @update="updateParams"/>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-12">
                <OptionCardTable :contextMode="contextMode" title="Tableau des évaluations"
                    :columns="columns" :rows="rows" :meta="meta" :busy="busyTable"
                    @reload="loadView"/>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-7">
                <OptionCardInfo :contextMode="contextMode"/>
            </div>
            <div class="col-lg-5">
                <OptionCardPlus :contextMode="contextMode"/>
            </div>
        </div>
    </div>
</template>

<script>
import OptionCard from '../components/OptionCard'
import OptionCardParameter from '../components/OptionCardParameter'
import OptionCardTable from '../components/OptionCardTable'
import OptionCardTitle from '../components/OptionCardTitle'
import OptionCardPlus from '../components/OptionCardPlus'
import OptionCardInfo from '../components/OptionCardInfo'
import { getDatas } from '../../utils/api'

export default {
    name: "viewEval",

    components: {
        OptionCard,
        OptionCardTable,
        OptionCardTitle,
        OptionCardPlus,
        OptionCardInfo,
        OptionCardParameter,
    },
    data() {
        return {
            contextMode: 'eval',
            params: [],
            paramMode: '*',
            busyTable: false,
            rows: [],
            columns: [],
            meta: {
                "numerotable": false,
                "groupable": true,
                "header": {
                    "searchable": false,
                    "creatable": false,
                    "exportable": { "xls": false, "pdf": false, "doc": false, "png": false }, //"exportable"
                    "importable": { "xls": false, "pdf": false, "doc": false, "png": false } //"importable"
                },
                "actions": { "detaillable": false, "updatable": false, "deletable": false }
            },//{},
        }
    },
    watch: {
        currentRoleName: function(val) {
            this.loadView()
        },
    },
    computed: {
        currentRoleName() {
            return this.$store.getters['auth/currentRoleName']
        },
    },
    mounted() {
        this.loadView()
    },
    methods: {
        updateParams(payload) { //payload = {params, paramMode}
            this.params = this.paramify(payload.params)
            this.paramMode = payload.paramMode
            //console.log('params', this.params, ';\paramMode', this.paramMode)
            this.loadView()
        },
        paramify(arrOptions) {
            let arrParams = []
            arrOptions.forEach(objOption => {
                if (!_.isEmpty(objOption.currentValue)) {
                    arrParams.push({
                        id: objOption.currentValue.id,
                        label: objOption.label
                    })
                }
            });
            return arrParams
        },
        loadView() {
            this.busyTable = true
            getDatas(this.contextMode, this.currentRoleName, this.params, this.paramMode).then( ({ data }) => {
                this.columns = data.columns,
                this.rows = data.rows
                this.meta = data.meta
                this.busyTable = false
            }).catch( (error) => {
                console.log('error caught!', error)
                this.busyTable = false
            })
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
</style>
