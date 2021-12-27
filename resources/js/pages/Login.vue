<template>
    <q-layout>
    <q-page-container>
    <q-page class="bg-light-green window-height window-width row justify-center items-center">
        <div class="column">
            <div class="row">
                <h5 class="text-h5 text-white q-my-md">Accounting Login</h5>
            </div>
            <div class="row">
                <q-card square bordered class="q-pa-lg shadow-1">
                    <q-card-section>
                        <q-form class="q-gutter-md" autocomplete="off">
                            <q-input autocomplete="off" square filled clearable v-model="form.username"  label="username" />
                            <q-input autocomplete="off"  square filled clearable v-model="form.password" type="password" label="password" />
                        </q-form>
                    </q-card-section>
                    <q-card-actions class="q-px-md">
                        <q-btn unelevated color="light-green-7" size="lg" class="full-width" label="Login" @click="login"/>
                    </q-card-actions>
                    <q-card-section class="text-center q-pa-none">
                    </q-card-section>
                </q-card>
            </div>
        </div>
    </q-page>
    </q-page-container>
    </q-layout>
</template>

<script>
import {Notify} from 'quasar'

export default {
    name: "Login",
    data(){
        return {
            form: {
                username: '',
                password: ''
            }
        }
    },
    mounted() {
        if(localStorage.getItem('user')){
            this.$router.replace('/')
        }
    },
    methods: {
        login(){
            this.$axios.post('/api/login', this.form)
            .then((res) =>{
                localStorage.setItem('user', this.form.username)
                this.$router.replace('/')
            }).catch(e => {
                console.error(e)
                Notify.create({
                    message: 'Login failed!'
                })
            })
        }
    }
}
</script>

<style scoped>

</style>
