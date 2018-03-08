<template>
    <div>
        <!--Example Elements-->
        <section class="hero is-info">
            <div class="hero-body">
                <div class="container">
                    <div class="columns">
                        <div class="column is-8 is-offset-3" style="display: flex; align-items: center;">
                            <div style="text-align: center">
                                <h1 class="title text-medium-grey" style="margin-bottom: .5rem">
                                    72dpi Application Form
                                </h1>
                                <hr class="is-marginless">
                                <h2 class="subtitle text-light-grey" style="margin-top: .5rem">
                                    developed by Dat Pham
                                </h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="section" style="padding-top: .5rem">
            <div class="container">
                <div class="columns">
                    <div class="column is-8 is-offset-2">
                        <horizontal-stepper :steps="steps" @completed-step="completeStep" :top-buttons="true"
                                            @active-step="isStepActive" @stepper-finished="finish"></horizontal-stepper>
                    </div>
                </div>
            </div>
        </section>

        <footer class="footer">
            <div class="container">
                <div class="content has-text-centered">
                    <p>
                        <small>Dependencies for this demo: <a href="https://github.com/PygmySlowLoris/vue-stepper">vue-stepper</a> <a href="http://bulma.io">bulma</a></small>
                    </p>
                </div>
            </div>
        </footer>
    </div>
</template>

<script>
    import axios from 'axios';
    import HorizontalStepper from 'vue-stepper';
    import StepOne from './StepOne.vue';
    import StepTwo from './StepTwo.vue';
    import StepThree from './StepThree.vue';

    export default {
        name: 'app',
        components: {
            HorizontalStepper
        },
        data(){
            return {
                steps: [
                    {
                        icon: 'assignment',
                        name: 'application-form',
                        title: 'Application Form',
                        // subtitle: 'Subtitle sample',
                        component: StepOne,
                        completed: false
                    },
                    {
                        icon: 'work',
                        name: 'terms-conditions',
                        title: 'Terms & Conditions',
                        // subtitle: 'Subtitle sample',
                        component: StepTwo,
                        completed: false
                    },
                    {
                        icon: 'verified_user',
                        name: 'third',
                        title: 'Confirmation',
                        // subtitle: 'Subtitle sample',
                        component: StepThree,
                        completed: false
                    }
                ],
                activeStep: 0
            }
        },
        computed: {},
        methods: {
            completeStep(payload) {
                window.scrollTo(0, 0);
                this.steps.forEach((step) => {
                    if (step.name === payload.name) {
                        step.completed = true;
                    }
                })
            },
            isStepActive(payload) {
                this.steps.forEach((step) => {
                    if (step.name === payload.name) {
                        if(step.completed === true) {
                            step.completed = false;
                        }
                    }
                })
            },
            finish(payload) {
              return this.createUser()
                  .then(() => {
                      window.localStorage.setItem('created', true);
                      window.location.reload();
                  })
            },
            createUser() {
                const data = new FormData();
                data.append('firstname', window.localStorage.getItem('firstname'));
                data.append('lastname', window.localStorage.getItem('lastname'));
                data.append('email', window.localStorage.getItem('email'));
                data.append('phone', window.localStorage.getItem('phone'));
                data.append('dob', window.localStorage.getItem('dob'));
                data.append('gender', window.localStorage.getItem('gender'));
                data.append('address', window.localStorage.getItem('address'));
                data.append('token', window.localStorage.getItem('token'));
                if (window._uploadFile) {
                    data.append('cv', window._uploadFile);
                }

                return axios.post('/user', data);
            },
        }
    }
</script>

<style scoped>
    #app {
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        text-align: center;
        color: #2c3e50;
    }
    .pointer {
        cursor: pointer;
    }
    h1, h2 {
        font-weight: normal;
    }
    hr {
        background-color: transparent;
        border: none;
        display: block;
        height: inherit;
        margin: 1.5rem 0;
        border-top: dashed 1px;
    }
    li {
        display: inline-block;
        margin: 0 10px;
    }
    a {
        color: #0b99b9;
        text-decoration: underline;
    }
    .text-medium-grey {
        color: #333;
    }
    .text-light-grey {
        color: #888;
    }
    .box.formated {
        position: relative;
        padding: 0;
    }
    .box.formated .heading {
        font-size: 1rem;
        text-transform: capitalize;
        padding: .8rem 1.5rem;
        background-color: #fafafa;
    }
    .box.formated .content {
        padding: 1rem 2rem;
    }
    i.top-left {
        position: absolute;
        left: 1.5rem;
        top: 0.8rem;
    }
    .vertical-separator {
        display: flex;
        justify-content: space-around;
    }
    .vertical-separator .line {
        border-right: 1px solid #cccccc;
    }
</style>
