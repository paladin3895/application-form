<template>
    <div class="card" style="margin: 3rem">
        <header class="card-header">
            <div style="text-align: center; padding: 10px; width: 100%;">Please read and agree to our terms &amp; conditions</div>
        </header>
        <div class="card-content">
            <div class="content" style="text-align: justify;">
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer a sapien aliquam, volutpat mi sit amet, porta magna. Fusce sed luctus mauris, at viverra est. Praesent at erat ut metus fringilla malesuada et lobortis metus. Morbi libero turpis, consectetur in ipsum at, placerat pulvinar arcu. Suspendisse dictum urna vitae lectus elementum, nec blandit elit ornare. Cras molestie, enim vel mollis imperdiet, metus lorem fermentum nulla, at eleifend tortor purus ac sem. Nulla convallis orci eget velit blandit, non gravida justo gravida.</p>
                <p>Aenean pellentesque feugiat tortor, eget convallis libero luctus sed. Duis sapien lorem, gravida sed blandit in, condimentum at metus. Aenean finibus enim ipsum, id tincidunt purus semper eget. Proin efficitur suscipit lobortis. Nam est velit, eleifend non placerat eu, vulputate vitae diam. Curabitur imperdiet lectus tincidunt ante aliquam, vel tempor magna porta. Donec id mi ut mi blandit blandit. Aliquam feugiat risus ac arcu pellentesque, eu elementum erat pretium</p>
            </div>
        </div>
        <footer class="card-footer">
            <label class="checkbox" style="padding: 10px 20px;">
                <input :class="['checkbox']"  type="checkbox" v-model="accepted"> I agree to the terms and conditions
            </label>
        </footer>
    </div>
</template>

<script>
    import { validationMixin } from 'vuelidate'
    import { required } from 'vuelidate/lib/validators'

    export default {
        props: ['clickedNext', 'currentStep'],
        mixins: [validationMixin],
        data() {
            return { accepted: false }
        },
        validations: {
            accepted: {
                required,
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
    }
</script>
