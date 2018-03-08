<template>
    <div style="padding: 2rem 3rem; text-align: left;">
        <p>We have sent an email with confirmation code to your registered email. Please check and input the confirmation code here to finish.</p>
        <div class="field">
            <label class="label">Confirmation Code</label>
            <div class="control">
                <input :class="['input', ($v.token.$error) ? 'is-danger' : '']" type="text" placeholder="Code input"
                       v-model="token">
            </div>
            <p v-if="$v.token.$error" class="help is-danger">This code is invalid or expired</p>
        </div>
        <a v-if="!tokenSentRecently" v-on:click="sendToken">Resend token</a>
    </div>
</template>

<script>
    import axios from 'axios';
    import { validationMixin } from 'vuelidate'
    import { required } from 'vuelidate/lib/validators'

    export default {
        props: ['clickedNext', 'currentStep'],
        mixins: [validationMixin],
        data() {
            return { token: '', tokenSentRecently: false };
        },
        validations: {
            token: {
                required,
                isMatching(value) {
                    const email = window.localStorage.getItem('email');
                    return axios.get(`/token/${value || '_'}/${email}`)
                        .then((res) => res.data.isValid);
                }
            },
        },
        watch: {
            $v: {
                handler: function (val) {
                    if(val.$invalid) {
                        this.$emit('can-continue', { value: false });
                        setTimeout(() => {
                            this.$emit('change-next', { nextBtnValue: false });
                        }, 3000)
                    } else {
                        this.$emit('can-continue', { value: true });
                    }
                },
                deep: true
            },
            token(val) {
                window.localStorage.setItem('token', val);
            },
            clickedNext(val) {
                if(val === true) {
                    this.$v.$touch();
                }
            }
        },
        mounted() {
            const tokenSent = window.localStorage.getItem('tokenSent');
            if (!tokenSent) {
                this.sendToken();
            }
            if(!this.$v.$invalid) {
                this.$emit('can-continue', { value: true });
            } else {
                this.$emit('can-continue', { value: false });
            }
        },
        methods: {
            sendToken() {
                const email = window.localStorage.getItem('email');
                this.tokenSentRecently = true;
                setTimeout(() => {
                    this.tokenSentRecently = false;
                }, 5000)
                axios.post('/token', {
                    email
                }).then(() => window.localStorage.setItem('tokenSent', true));
            },
        }
    }
</script>
