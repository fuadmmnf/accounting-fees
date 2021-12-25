<template>
    <div class="q-pa-lg q-ml-md">
        <q-form
            @submit="storeApplication"
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
                    v-model="applicationForm.amount"
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
                    title="Fees"
                    dense
                    :rows="getApplicationFields"
                    :columns="columns"
                />


            <div>
                <q-btn label="Generate Fees" type="button" color="primary" @click="generateFees"/>
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
                    name: 'optional_field_name',
                    required: true,
                    label: 'Field Name',
                    field: row => row.optional_field_name,
                },
                { name: 'unit',  label: 'Rate(%|tk)',  field: row => `${row.amount} ${row.unit == 0? '%': 'tk'}`},
                { name: 'amount',  label: 'Value', field: row => `${(row.unit == 0? (row.application_amount * row.amount/100.0): row.amount)} tk`, sortable: true },

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
        },
        storeApplication() {
            this.$axios.post('/api/applications', this.applicationForm)
                .then((res) => {
                    Notify.create({
                        message: 'Application Created Successfully!'
                    })

                    this.applicationForm = generateApplicationTemplate()
                    scrollTo(0, 0)
                })
        }
    }
}
</script>

<style scoped>

</style>
