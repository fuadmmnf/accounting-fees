<template>
    <div class="q-pa-lg q-ml-md">
        <q-form
            @submit="storeApplication"
            class="q-gutter-md"
        >
            <div class="row">
                <q-input class="col-md-3 col-xs-12 q-pr-md" filled v-model="applicationForm.date" mask="date" :rules="['date']" label="Date">
                    <template v-slot:append>
                        <q-icon name="event" class="cursor-pointer">
                            <q-popup-proxy ref="qDateProxy" cover transition-show="scale" transition-hide="scale">
                                <q-date v-model="applicationForm.date">
                                    <div class="row items-center justify-end">
                                        <q-btn v-close-popup label="Close" color="primary" flat />
                                    </div>
                                </q-date>
                            </q-popup-proxy>
                        </q-icon>
                    </template>
                </q-input>

                <q-select class="col-md-4 col-xs-12 q-pr-md q-pb-md" filled v-model="applicationForm.category_id" :options="categories" label="Category" />

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

const generateApplicationTemplate = () => {
    return {
        category_id: '',
        date: new Date(),
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

        },
        storeApplication() {

        }
    }
}
</script>

<style scoped>

</style>
