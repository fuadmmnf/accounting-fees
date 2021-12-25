<template>
    <div class="q-pa-lg q-ml-md">
        <q-form
            @submit="storeApplication"
            class="q-gutter-md"
        >
            <div class="row">
                <q-input class="col-md-3 col-xs-12 q-pr-md q-pb-md" filled v-model="applicationForm.date"  label="Date">
                    <template v-slot:append>
                        <q-icon name="event" class="cursor-pointer">
                            <q-popup-proxy ref="qDateProxy" cover transition-show="scale" transition-hide="scale">
                                <q-date v-model="applicationForm.date" today-btn mask="DD/MM/YYYY">
                                    <div class="row items-center justify-end">
                                        <q-btn v-close-popup label="Close" color="primary" flat />
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
                          label="Category" />

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


            <div>
                <q-btn label="Submit" type="submit" color="primary"/>
            </div>
        </q-form>
    </div>
</template>

<script>
import { Notify } from 'quasar'
import { date } from 'quasar'


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
