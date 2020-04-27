<template>
    <div>
        <div>
            <vue-good-table ref="generic-good-table"
                theme="default" styleClass="vgt-table bordered condensed"
                :rows="rows" :columns="dataColumns"
                :line-numbers="numerotable"
                :groupOptions="{ enabled: groupable }"
                :search-options="{
                    enabled: header.filterable,
                    placeholder: 'Rechercher dans cette table',
                }"
            >
                <div slot="table-actions">
                    <b-button-group class="mr-1">
                        <b-button variant="outline-light" v-if="header.creatable">
                            <font-awesome-icon icon="plus"/>
                        </b-button>

                        <b-dropdown right variant="outline-light" v-if="!isFullyFalse(header.importable)">
                            <template v-slot:button-content>
                                <font-awesome-icon icon="upload"/>
                            </template>
                            <b-dropdown-header>Importer à partir de ...</b-dropdown-header>
                            <template v-if="header.importable.xls">
                                <b-dropdown-divider></b-dropdown-divider>
                                <b-dropdown-item-button v-if="header.importable.xls">
                                    Fichier Excel (xls, xlsx, csv)
                                </b-dropdown-item-button>
                            </template>
                            <template v-if="header.importable.doc">
                                <b-dropdown-divider></b-dropdown-divider>
                                <b-dropdown-item-button>
                                    Fichier Word (doc, docx, odf, ...)
                                </b-dropdown-item-button>
                            </template>
                            <template v-if="header.importable.pdf">
                                <b-dropdown-divider></b-dropdown-divider>
                                <b-dropdown-item-button>
                                    Fichier PDF
                                </b-dropdown-item-button>
                            </template>
                            <template v-if="header.importable.img">
                                <b-dropdown-item-button>
                                    Fichier Image (png, jpeg)
                                </b-dropdown-item-button>
                            </template>
                        </b-dropdown>
                        <b-dropdown right variant="outline-light" v-if="!isFullyFalse(header.exportable)">
                            <template v-slot:button-content>
                                <font-awesome-icon icon="download"/>
                            </template>
                            <b-dropdown-header>Exporter vers ...</b-dropdown-header>
                            <template v-if="header.exportable.xls">
                                <b-dropdown-divider></b-dropdown-divider>
                                <b-dropdown-item-button>
                                    Fichier Excel (xls, xlsx, csv)
                                </b-dropdown-item-button>
                            </template>
                            <template v-if="header.exportable.doc">
                                <b-dropdown-divider></b-dropdown-divider>
                                <b-dropdown-item-button>
                                    Fichier Word (doc, docx, odf, ...)
                                </b-dropdown-item-button>
                            </template>
                            <template v-if="header.exportable.pdf">
                                <b-dropdown-divider></b-dropdown-divider>
                                <b-dropdown-item-button>
                                    Fichier PDF
                                </b-dropdown-item-button>
                            </template>
                            <template v-if="header.exportable.img">
                                <b-dropdown-divider></b-dropdown-divider>
                                <b-dropdown-item-button>
                                    Fichier Image (png, jpeg)
                                </b-dropdown-item-button>
                            </template>
                        </b-dropdown>
                    </b-button-group>
                </div>

                <div slot="emptystate">
                    Nous n'avons trouvé aucune information à afficher.
                </div>

                <div slot="table-actions-bottom">
                    <!--Pagination-->
                </div>

                <template slot="table-row" slot-scope="props">
                    <span v-if="props.column.field == 'actions'">
                        <b-dropdown split block
                            split-variant="outline-dark"
                            variant="outline-dark"
                            @click="actionRead(props)"
                            v-if="!isEditableRow(props.index)"
                        >
                            <template v-slot:button-content>
                                <font-awesome-icon icon="eye"/> Voir
                            </template>

                            <b-dropdown-item-button @click="startUpdate(props)">
                                <font-awesome-icon icon="pen"/> Modifier
                            </b-dropdown-item-button>

                            <b-dropdown-divider></b-dropdown-divider>

                            <b-dropdown-item-button @click="actionDelete(props)">
                                <font-awesome-icon icon="eraser"/> Supprimer
                            </b-dropdown-item-button>
                        </b-dropdown>

                        <b-button-group class="mr-1" v-else>
                            <b-button variant="outline-dark" @click="actionUpdate(props)">
                                <font-awesome-icon icon="check"/>
                            </b-button>
                            <b-button variant="outline-dark" @click="dissmissUpdate">
                                <font-awesome-icon icon="times"/>
                            </b-button>
                        </b-button-group>
                    </span>

                    <span v-else-if="isEditableRow(props.index)">
                        <b-form-input v-model="editingObj[props.column.field]"></b-form-input>
                    </span>

                    <span v-else style="padding: .75em;">
                        {{ props.formattedRow[props.column.field] }}
                    </span>
                </template>

            </vue-good-table>
        </div>
    </div>
</template>


<script>
import 'vue-good-table/dist/vue-good-table.css'
import { VueGoodTable } from 'vue-good-table';
import { crudCreate, crudRead, crudUpdate, crudDelete } from '../../utils/api'

export default {
    name: 'generic-table',
    components: {
        VueGoodTable,
    },
    data() {
        return {
            selectionMultiple: false,
            editingIndex: null,
            editingObj: {},
        }
    },
    props: {
        columns: Array,
        rows: Array,
        busy: Boolean,
        meta: Object,
    },
    computed: {
        currentRoleName() {
            return this.$store.getters['auth/currentRoleName']
        },
        numerotable() {
            return this.meta.numerotable
        },
        groupable() {
            return this.meta.groupable
        },
        header() {
            return this.meta.header ? this.meta.header : {
                filterable: false,
                creatable: false,
                exportable: {
                    xls: false,
                    pdf: false,
                    doc: false,
                    img: false
                },
                importable: {
                    xls: false,
                    pdf: false,
                    doc: false,
                    img: false
                },
            }
        },
        actions() {
            return this.meta.actions ? this.meta.actions : {
                detaillable: false,
                updatable: false,
                deletable: false
            }
        },
        dataColumns() {
            let columns = this.columns
            _.each(columns, function(obj) {
                obj.tdClass = 'align-middle'
            })
            if (columns) {
                columns.push({
                    label: 'Actions',
                    field: 'actions',
                    width: '135px',
                    tdClass: 'align-middle text-center',
                    filterable: false,
                    sortable: false
                })
            }
            return columns
        }
    },
    methods: {
        isEditableRow(currentPropsIndex) {
            return !_.isNull(this.editingIndex) && currentPropsIndex == this.editingIndex
        },
        isFullyFalse(obj) {
            return _.isEmpty(
                _.filter(
                    _.values(
                        obj
                    ), function(bool) { return bool != false }
                )
            )
        },
        //crud functions
        startUpdate(currentProps) {
            this.editingIndex = currentProps.index //currentProps.row.originalIndex
            this.editingObj = _.assign(currentProps.formattedRow)
        },
        dissmissUpdate() {
            this.editingIndex = null
            this.editingObj = null
        },
        actionUpdate(currentProps) {
            //-----valider la modification
            //
            crudUpdate('eval', this.currentRoleName, currentProps.row.id).then( ({ data }) => {
                if (data) { //has been updated
                    console.log(this.rows[currentProps.index], this.editingObj)
                    //this.rows[currentProps.index] = _.assign(this.editingObj)
                } else { //has not
                    console.log(false)
                }
            }).catch( (error) => {
                console.log('error caught when deleting!', error)
            })
            this.editingIndex = null
            this.editingObj = null
        },
        actionDelete(currentProps) {
            //-----valider la suppression
            //
            crudDelete('eval', this.currentRoleName, currentProps.row.id).then( ({ data }) => {
                if (data) { //has been deleted
                    console.log(true, this.rows, currentProps)
                    this.rows.splice( currentProps.index, 1)
                } else { //has not
                    console.log(false)
                }
            }).catch( (error) => {
                console.log('error caught when deleting!', error)
            })
        },
        actionRead(currentProps) {

        },
        actionCreate() {

        }
    }
}
</script>

<style>
/*.table th, .table td {
    vertical-align: middle;
}*/
.table td {
    vertical-align: middle;
    padding: 0;
}
div.vgt-global-search__input.vgt-pull-left input.vgt-input.vgt-pull-left {
    height: 100%;
}
</style>
