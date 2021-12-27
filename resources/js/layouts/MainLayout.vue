<template>
    <q-layout view="lHh Lpr lFf">
        <q-header elevated>
            <q-toolbar>
                <q-btn
                    flat
                    dense
                    round
                    icon="menu"
                    aria-label="Menu"
                    @click="leftDrawerOpen = !leftDrawerOpen"
                />

                <q-toolbar-title>
                    Fees Accounting
                </q-toolbar-title>

                <div>
                    <q-btn class="q-mr-lg" icon="add" :to="{name: 'create'}" color="white" text-color="primary" label="Create"></q-btn>
                    <q-btn class="q-mr-lg" icon="add" color="white" text-color="primary" label="Category" @click="showCategoryCreateDialog = true"></q-btn>
                    <q-dialog v-model="showCategoryCreateDialog" persistent>
                        <q-card style="min-width: 400px">
                            <q-card-section>
                                <div class="text-h6">Create Category</div>
                            </q-card-section>

                            <q-card-section class="q-pt-none">
                                <div class="row">

                                    <q-input class="col-10  q-pb-sm q-pr-sm" dense
                                             v-model="categoryForm.name" label="Name"/>
                                </div>

                            </q-card-section>

                            <q-card-actions align="right" class="text-primary">
                                <q-btn flat label="Cancel" v-close-popup/>
                                <q-btn flat label="Add" @click="createCategory"/>
                            </q-card-actions>
                        </q-card>
                    </q-dialog>
                    <q-btn icon="logout" @click="logout"></q-btn>
                </div>
            </q-toolbar>
        </q-header>

        <q-drawer
            v-model="leftDrawerOpen"
            show-if-above
            bordered
            content-class="bg-grey-1"
        >
            <q-list>
                <q-item-label
                    header
                    class="text-grey-8"
                >
                    Essential Links
                </q-item-label>
                <EssentialLink
                    v-for="link in essentialLinks"
                    :key="link.title"
                    v-bind="link"
                />
            </q-list>
        </q-drawer>

        <q-page-container>
            <transition appear enter-active-class="animate__animated animate__zoomIn">
                <router-view/>
            </transition>
        </q-page-container>
    </q-layout>
</template>

<script>
import EssentialLink from '../components/EssentialLink'
import {Notify} from 'quasar'

export default {
    name: 'MainLayout',

    components: {
        EssentialLink
    },

    data() {
        return {
            leftDrawerOpen: false,
            essentialLinks: [
                {
                    title: 'List',
                    caption: 'application list date|category wise',
                    icon: 'table_chart',
                    link: {name: 'log'},
                    blank: false
                },
                {
                    title: 'Create',
                    caption: 'create new application entry',
                    icon: 'add',
                    link: {name: 'create'},
                    blank: false
                },

                {
                    title: 'Report',
                    caption: 'daily | monthly | yearly accounting reports',
                    icon: 'assignment',
                    link: {name: 'report'},
                    blank: false
                },

            ],
            categoryForm: {
                name: ''
            },
            showCategoryCreateDialog: false
        }
    },
    methods: {
        createCategory(){
          this.$axios.post('/api/categories', this.categoryForm)
          .then((res) => {
              this.categoryForm.name = ''
              Notify.create({
                  message: 'Category Created Successfully!'
              })
              this.$root.$emit('category-created')
              this.showCategoryCreateDialog = false
          })
        },
        logout(){
            localStorage.removeItem('user')
            this.$router.replace('/login')
        }
    }
}
</script>
