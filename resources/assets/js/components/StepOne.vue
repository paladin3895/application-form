<template>
    <div style="padding: 2rem 3rem; text-align: left;">
        <p>Please take time to provide your info to following fields (* required fields):</p>
        <div class="field">
            <label class="label">First Name *</label>
            <div class="control">
                <input :class="['input', ($v.firstname.$error) ? 'is-danger' : '']" type="text" placeholder="Your first name"
                       v-model="firstname">
            </div>
            <p v-if="$v.firstname.$error" class="help is-danger">Your firstname is required</p>
        </div>
        <div class="field">
            <label class="label">Last Name *</label>
            <div class="control">
                <input :class="['input', ($v.lastname.$error) ? 'is-danger' : '']" type="text" placeholder="Your last name"
                       v-model="lastname">
            </div>
            <p v-if="$v.lastname.$error" class="help is-danger">Your lastname is required</p>
        </div>
        <div class="field">
            <label class="label">Email *</label>
            <div class="control">
                <input :class="['input', ($v.email.$error) ? 'is-danger' : '']"  type="text" placeholder="Your email" v-model="email">
            </div>
            <p v-if="$v.email.$error" class="help is-danger">This email is invalid or already registered</p>
        </div>
        <div class="field">
            <label class="label">Phone *</label>
            <div class="control">
                <input :class="['input', ($v.phone.$error) ? 'is-danger' : '']"  type="text" placeholder="Your phone number" v-model="phone">
            </div>
            <p v-if="$v.phone.$error" class="help is-danger">Your phone number is required</p>
        </div>
        <div class="field">
            <label class="label">Date of birth *</label>
            <div class="control">
                <input :class="['input', ($v.dob.$error) ? 'is-danger' : '']"  type="date" placeholder="Your birthdate" v-model="dob">
            </div>
            <p v-if="$v.dob.$error" class="help is-danger">This field is required</p>
        </div>
        <div class="field">
            <label class="label">Gender</label>
            <div class="control">
                <div class="select">
                    <select v-model="gender">
                        <option disabled value="">Please select one</option>
                        <option value="M">Male</option>
                        <option value="F">Female</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="field">
            <label class="label">Address *</label>
            <div class="control">
                <input :class="['input', ($v.address.$error) ? 'is-danger' : '']"  type="text" placeholder="Your address" v-model="address">
            </div>
            <p v-if="$v.address.$error" class="help is-danger">Your address is required</p>
        </div>
        <div class="field">
            <label class="label">Resume</label>
            <div class="control">
                <input :class="['input', (fileInvalid) ? 'is-danger' : '']"  type="file" placeholder="Your CV" v-on:change="processFile($event)">
            </div>
            <p v-if="fileInvalid" class="help is-danger">This file is invalid</p>
        </div>
    </div>
</template>

<script>
    import { validationMixin } from 'vuelidate'
    import { required, email } from 'vuelidate/lib/validators'
    export default {
        props: ['clickedNext', 'currentStep'],
        mixins: [validationMixin],
        data() {
            const defaults = {
                firstname: '',
                lastname: '',
                email: '',
                phone: '',
                dob: '',
                gender: '',
                address: '',
                fileInvalid: false,
            }

            Object.keys(defaults)
                .forEach(key => {
                    const storedValue = window.localStorage.getItem(key);
                    if (storedValue) {
                        defaults[key] = storedValue;
                    }
                })

            return defaults;
        },
        validations: {
            firstname: {
                required
            },
            lastname: {
                required
            },
            email: {
                required,
                email,
                isUnique(value) {
                    return axios.get(`/user/${value || '_'}`)
                        .then((res) => !res.data.exists);
                }
            },
            phone: {
                required
            },
            dob: {
                required,
            },
            address: {
                required,
            },
            fileInvalid: {
                isNotTrue(value) {
                    return !value;
                }
            }
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
            firstname(value) {
                this.storeData('firstname', value);
            },
            lastname(value) {
                this.storeData('lastname', value);
            },
            email(value) {
                this.storeData('email', value);
            },
            phone(value) {
                this.storeData('phone', value);
            },
            dob(value) {
                this.storeData('dob', value);
            },
            gender(value) {
                this.storeData('gender', value);
            },
            address(value) {
                this.storeData('address', value);
            },
            clickedNext(val) {
                if(val === true) {
                    this.$v.$touch();
                }
            }
        },
        mounted() {
            if(!this.$v.$invalid) {
                this.$emit('can-continue', { value: true });
            } else {
                this.$emit('can-continue', { value: false });
            }
        },
        methods: {
            storeData(key, value) {
                window.localStorage.setItem(key, value);
            },
            processFile(event) {
                const allowedTypes = [
                    'application/pdf',
                    'application/msword',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    'application/vnd.oasis.opendocument.text',
                ]
                window._uploadFile = event.target.files[0];
                if (allowedTypes.indexOf(window._uploadFile.type) >= 0) {
                    this.fileInvalid = false;
                } else {
                    this.fileInvalid = true;
                }
            }
        }
    }
</script>
