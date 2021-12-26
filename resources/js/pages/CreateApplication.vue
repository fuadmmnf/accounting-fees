<template>
    <div class="q-pa-lg q-ml-md">
        <q-form
            @submit="storeApplication"
            @reset="clearForm"
            class="q-gutter-md"
        >
            <div class="row">
                <q-input class="col-md-3 col-xs-12 q-pr-md q-pb-md" filled v-model="applicationForm.date" label="Date">
                    <template v-slot:append>
                        <q-icon name="event" class="cursor-pointer">
                            <q-popup-proxy ref="qDateProxy" cover transition-show="scale" transition-hide="scale">
                                <q-date v-model="applicationForm.date" today-btn mask="DD/MM/YYYY">
                                    <div class="row items-center justify-end">
                                        <q-btn v-close-popup label="Close" color="primary" flat/>
                                    </div>
                                </q-date>
                            </q-popup-proxy>
                        </q-icon>
                    </template>
                </q-input>

                <q-select class="col-md-4 col-xs-12 q-pr-md q-pb-md" filled v-model="applicationForm.category_id"
                          :options="categories"
                          option-label="name"
                          option-value="id"
                          map-options
                          emit-value
                          label="Category"/>

                <q-input
                    class="col-md-3 col-xs-12 q-pr-md"
                    filled
                    type="number"
                    v-model.number="applicationForm.amount"
                    label="Application Value"
                    lazy-rules
                    :rules="[
          val => val !== null && val !== '' || 'Please type value',
          val => val > 0  || 'Please type a real value'
        ]"
                />

            </div>

                <q-table
                    v-if="getApplicationFields.length"
                    class="q-mb-lg"
                    title="Fees"
                    dense
                    :data="getApplicationFields"
                    :columns="columns"
                    hide-pagination
                >
                    <template v-slot:body="props">
                        <q-tr :props="props">
                            <q-td key="name" :props="props">
                                {{ props.row.optional_field_name }}
                            </q-td>
                            <q-td key="unit" :props="props">
                                {{ `${props.row.amount} ${props.row.unit == 0? '%': 'tk'}` }}
                            </q-td>
                            <q-td key="amount" :props="props">
                                {{ `${(props.row.unit == 0? (props.row.application_amount * props.row.amount/100.0): props.row.amount)} tk` }}
                            </q-td>
                        </q-tr>
                    </template>
                </q-table>


            <div>
                <q-btn v-if="!getApplicationFields.length" label="Generate Fees" type="button" color="primary" @click="generateFees"/>
                <div v-else class="row">
                    <q-btn class="col-3 q-mr-md" label="Submit" type="submit" color="primary"/>
                    <q-btn class="col-3 q-mr-md" label="Reset" type="reset" color="white" text-color="primary"/>
                </div>
            </div>
        </q-form>
    </div>
</template>

<script>
import {Notify} from 'quasar'
import {date} from 'quasar'


const generateApplicationTemplate = () => {
    return {
        category_id: '',
        date: date.formatDate(Date.now(), 'DD/MM/YYYY'),
        amount: '',
        fields: []
    }
}



export default {
    name: "CreateApplication",
    data() {
        return {
            columns: [
                {
                    name: 'name',
                    required: true,
                    align: 'left',
                    label: 'Field Name',
                    field: row => row.optional_field_name,
                },
                { name: 'unit',  label: 'Rate(%/tk)', align: 'left', field: row => `${row.amount} ${row.unit == 0? '%': 'tk'}`},
                { name: 'amount',  label: 'Value', align: 'left', field: row => `${(row.unit == 0? (row.application_amount * row.amount/100.0): row.amount)} tk`, sortable: true },

            ],
            categories: [],
            applicationForm: generateApplicationTemplate(),
        }
    },
    computed: {
        getApplicationFields(){
            return this.applicationForm.fields
        }
    },
    mounted() {
        this.loadCategories()
    },
    methods: {
        loadCategories() {
            this.$axios.get('/api/categories')
                .then((res) => {
                    this.categories = res.data
                })
        },
        generateFees() {
            let defaultFees = this.categories.find(c => c.id === this.applicationForm.category_id).defaultfees
            this.applicationForm.fields = defaultFees.map(df => {
                return {
                    field_id: df.field.id,
                    optional_field_name: df.field.name,
                    unit: df.unit,
                    amount: df.amount,
                    application_amount: this.applicationForm.amount
                }
            })
            console.log(this.applicationForm.fields)
        },
        clearForm(){
            this.applicationForm = generateApplicationTemplate()
        },
        storeApplication() {
            this.$axios.post('/api/applications', this.applicationForm)
                .then((res) => {
                    Notify.create({
                        message: 'Application Created Successfully!'
                    })

                    this.clearForm()
                    scrollTo(0, 0)
                })
        }
    }
}
</script>

<style scoped>

</style>
