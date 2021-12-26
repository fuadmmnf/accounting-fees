<template>
    <q-page class="q-pa-sm">
        <div class="row">

            <q-date class="col-md-7 col-xs-12" v-model="selectedDate" color="secondary"
                    today-btn mask="DD/MM/YYYY" subtitle="Deed report">
                <template>
                    <div class="flex flex-center items-center q-mb-md">
                        <q-select
                            class="col q-pr-xs " dense filled v-model.number="selectedCategory"

                            :options="categories" option-label="label"
                            emit-value
                            map-options label="Category"

                        />

                    </div>
                    <div class="q-gutter-sm">
                        <q-btn class="col-md-4 col-xs-12 q-my-xs q-px-xs" stretch color="primary" @click="generateReport(0)">Daily Report</q-btn>
                        <q-btn class="col-md-4 col-xs-12 q-my-xs q-px-xs" stretch color="accent" @click="generateReport(1)">Monthly Report</q-btn>
                        <q-btn class="col-md-4 col-xs-12 q-my-xs q-px-xs" stretch color="warning" @click="generateReport(2)">Yearly Report</q-btn>
                    </div>

                </template>
            </q-date>

        </div>

    </q-page>
</template>

<script>
import {date} from "quasar";

export default {
    name: 'Report',
    data() {
        return {
            categories: [],
            selectedCategory: 0,
            selectedDate: date.formatDate(Date.now(), 'DD/MM/YYYY'),
        }
    },
    mounted() {
        this.loadCategories()
    },
    methods: {
        loadCategories() {
            this.$axios.get('/api/categories')
                .then((res) => {
                    this.categories = [{value: 0, label: 'সকল ধরন'}, ...res.data.map(c => {
                        return {value: c.id, label: c.name}
                    })]
                })
        },
        async generateReport(type) {
            window.open(`/reports?type=${type}&category_id=${this.selectedCategory}&date=${this.selectedDate}`, '_blank')
        },
    }
}
</script>
