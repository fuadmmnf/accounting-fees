<template>
    <div class="q-pa-md">
        <q-table
            :title="`Deeds (${getFilteredApplications.length})`"
            :data="getFilteredApplications"
            :columns="columns"
            :rows-per-page-options="[0]"
            :pagination.sync="pagination"
            hide-pagination
        >
            <template v-slot:body="props">
                <q-tr :props="props">
                    <q-td key="category" :props="props">
                        {{ props.row.category.name }}
                    </q-td>
                    <q-td key="amount" :props="props">
                        {{ props.row.amount }} tk
                    </q-td>
<!--                    <q-td auto-width key="fees" :props="props">-->
<!--                        <div class="row">-->
<!--                            <q-chip size="sm" class="col-md-5 col-xs-12 q-pr-sm q-pb-sm" v-for="fee in props.row.applicationfees" :key="fee.id">{{`${fee.field_id == null? fee.optional_field_name: fee.field.name}: ${fee.unit == 0? (props.row.amount * fee.amount/100.0): fee.amount } tk`}}</q-chip>-->
<!--                        </div>-->
<!--                    </q-td>-->
                    <q-td key="action" :props="props">
                        <q-btn size="sm" color="accent" text-color="white" icon="info" @click="showDetail = true"></q-btn>
                        <q-dialog :id="'detail-' + props.row.id" v-model="showDetail" persistent>
                            <q-card >
                                <q-card-section class="row items-center">
                                    <span class="text-bold">Deed Detail</span>
                                </q-card-section>
                                <q-card-section class="q-pt-none q-pb-xs">
                                    <table>
                                        <tr>
                                            <th align="left">Field Name</th>
                                            <th align="left">Amount</th>
                                        </tr>

                                        <tr v-for="fee in props.row.applicationfees" :key="fee.id">
                                            <td align="left">{{fee.field_id == null? fee.optional_field_name: fee.field.name }}</td>
                                            <td align="left">{{fee.unit == 0? (props.row.amount * fee.amount/100.0): fee.amount}} tk</td>
                                        </tr>
                                    </table>
                                </q-card-section>
                                <q-card-actions align="right">
                                    <q-btn flat label="Cancel" color="primary" @click="showDetail=false" />
                                </q-card-actions>
                            </q-card>
                        </q-dialog>



                        <q-btn size="sm" color="negative" text-color="white" icon="delete" @click="confirmDeleteDialog = true"></q-btn>
                        <q-dialog :id="'confirmation-' + props.row.id" v-model="confirmDeleteDialog" persistent :key="props.row.id">
                            <q-card>
                                <q-card-section class="row items-center">
                                    <span class="q-ml-sm">Are you sure you want to delete?</span>
                                </q-card-section>

                                <q-card-actions align="right">
                                    <q-btn flat label="Cancel" color="primary" v-close-popup />
                                    <q-btn flat label="Confirm" color="primary" @click="() => {
                                        selectedApplicationId = props.row.id
                                        deleteApplication()
                                    }" />
                                </q-card-actions>
                            </q-card>
                        </q-dialog>
                    </q-td>
                </q-tr>
            </template>

            <template v-slot:top-right>
                <div class="row">
                    <q-input dense class="col-md-6 q-pr-xs q-pb-md" filled v-model="selectedDate" label="Date">
                        <template v-slot:append>
                            <q-icon name="event" class="cursor-pointer">
                                <q-popup-proxy ref="qDateProxy" cover transition-show="scale" transition-hide="scale">
                                    <q-date v-model="selectedDate" today-btn mask="DD/MM/YYYY">
                                        <div class="row items-center justify-end">
                                            <q-btn v-close-popup label="Close" color="primary" flat/>
                                        </div>
                                    </q-date>
                                </q-popup-proxy>
                            </q-icon>
                        </template>
                    </q-input>
                    <q-select class="col-md-5 q-pb-sm q-pr-xs" dense
                              v-model="selectedCategory"
                              :options="categories"
                              option-label="label"
                              map-options
                              emit-value
                              label="Category Filter"

                    />
                </div>

            </template>
        </q-table>

    </div>
</template>

<script>
import {date} from "quasar";

export default {
    name: "ApplicationLog",
    data(){
        return {
            confirmDeleteDialog: false,
            showDetail: false,
            selectedApplicationId: 0,
            selectedDate: date.formatDate(Date.now(), 'DD/MM/YYYY'),
            selectedCategory: 0,
            pagination: {
                page: 1,
                rowsPerPage: 0 // 0 means all rows
            },
            columns: [
                {
                    name: 'category',
                    align: 'left',
                    label: 'Category',
                    // field: row => row.category.name,
                },
                {
                    name: 'amount',
                    align: 'left',
                    label: 'Property Value',
                    // field: row => row.amount,
                },
                // {
                //     name: 'fees',
                //     align: 'left',
                //     label: 'Fees',
                //     style: 'width: 50%',
                //     // field: row => row.amount,
                // },
                {
                    name: 'action',
                    align: 'left',
                    label: 'Action(s)',
                    style: 'width: 10%',
                    // field: row => row.amount,
                },
            ],
            categories: [],
            applications: [],
        }
    },
    computed: {
        getFilteredApplications(){
            return (this.selectedCategory == 0)? this.applications: this.applications.filter(a => a.category_id == this.selectedCategory)
        }
    },
    watch: {
      selectedDate(val){
          this.loadApplications()
      }
    },
    mounted() {
        this.loadCategories()
        this.loadApplications()
    },
    methods: {
        loadCategories(){
            this.$axios.get('/api/categories')
            .then((res) => {
                this.categories = [{value: 0, label: 'সকল ধরন'} , ...res.data.map(c => {return {value: c.id, label: c.name}})]
            })
        },
        loadApplications(){
            this.$axios.get(`/api/applications?date=${this.selectedDate}&category_id=${this.selectedCategory}`)
            .then((res) => {
                this.applications = res.data
            })
        },
        deleteApplication(){

            this.$axios.delete(`api/applications/${this.selectedApplicationId}`)
            .then((res) => {
                this.confirmDeleteDialog = false

                this.loadApplications()
            })
        }
    }
}
</script>

<style scoped>

</style>
