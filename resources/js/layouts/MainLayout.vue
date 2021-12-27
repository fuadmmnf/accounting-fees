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

            ]
        }
    },
    methods: {
        logout(){
            localStorage.removeItem('user')
            this.$router.replace('/login')
        }
    }
}
</script>
