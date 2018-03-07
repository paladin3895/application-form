<template>
    <div>
        <!--Example Elements-->
        <section class="hero is-primary">
            <div class="hero-body">
                <div class="container">
                    <div class="columns">
                        <div class="column is-8 is-offset-3" style="display: flex; align-items: center;">
                            <div style="text-align: center">
                                <h1 class="title text-medium-grey" style="margin-bottom: .5rem">
                                    It's all done, thank you!
                                </h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="section" style="padding-top: 2.5rem">
            <div class="container">
                <div class="columns">
                    <div class="column is-8 is-offset-2">
                        <div class="card">
                            <div class="card-content">
                                <p class="subtitle">
                                    “{{ quote }}”
                                </p>
                                <p class="subtitle">
                                    {{ author }}
                                </p>
                            </div>
                            <footer class="card-footer">
                                <a v-on:click="reset" class="card-footer-item">Submit another application</a>
                            </footer>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>
</template>

<script>
const quotes = [
    {
        quote: "I’m a great believer in luck, and I find the harder I work, the more I have of it.",
        author: 'Thomas Jefferson',
    },
    {
        quote: "Choose a job you love, and you will never have to work a day in your life.",
        author: 'Author Unknown',
    },
    {
        quote: "Only those who dare to fail greatly can ever achieve greatly.",
        author: 'Robert F. Kennedy',
    },
]
    export default {
        data() {
            // Return a random quote
            return quotes[Math.floor(Math.random() * quotes.length)]
        },
        methods: {
            reset() {
                window.localStorage.clear();
                window.location.reload();
            }
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
