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

            <div v-if="applicationForm.fields.length">
                <q-table
                    title="Fees"
                    dense
                    :rows="applicationForm.fields"
                    :columns="columns"
                    row-key="name"
                />

<!--                <table>-->
<!--                    <tr>-->
<!--                        <th>Field Name</th>-->
<!--                        <th>Rate</th>-->
<!--                        <th>Value</th>-->
<!--                    </tr>-->
<!--                    <tr v-for="df in applicationForm.fields" :key="df.field_id">-->
<!--                        <td>{{df.optional_field_name}}</td>-->
<!--                        <td>{{df.amount}} {{(df.unit == 0? '%': 'tk')}}</td>-->
<!--                        <td>{{df.unit == 0? (applicationForm.amount * df.amount/100.0): df.amount}}</td>-->
<!--                    </tr>-->
<!--                </table>-->
            </div>

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

const columns = [
    {
        name: 'name',
        required: true,
        label: 'Field Name',
        align: 'center',
        field: row => row.optional_field_name,
    },
    { name: 'rate', align: 'center', label: 'Rate(%|tk)',  field: row => `${row.amount} ${row.unit == 0? '%': 'tk'}`},
    { name: 'value', align: 'center', label: 'Value', field: row => (row.unit == 0? (row.application_amount * row.amount/100.0): row.amount) + ' tk', sortable: true },

]


export default {
    name: "CreateApplication",
    data() {
        return {
            columns,
            categories: [],
            applicationForm: generateApplicationTemplate()
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
            const defaultFees = this.categories.find(c => c.id === this.applicationForm.category_id).defaultfees
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
