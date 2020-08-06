<template>
    <div class="col-12 p-1">
        <div class="card">
            <div class="card-header">
                <h4 style="margin-bottom:0;">{{ title }}</h4><small>{{ subtitle }}</small>
            </div>
            <div class="card-body">
                <div class="inner-card-body">
                    <ul class="pl-0" v-if="(params !== 'empty') && (params !== 'loading')">
                        <li v-for="(idx, key) in params">
                            <span class="bold">{{key}}:&nbsp;</span><span v-html="idx"></span>
                        </li>
                    </ul>
                    <p v-else-if="params === 'loading'">Loading...<i class="fad fa-spinner-third fa-spin"></i></p>
                    <p v-else>No Parameters Passed In.</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "listWidgetComponent",
        props: ['title', 'subtitle', 'params'],
        mounted() {
            console.log("ListWidget at your service! Params - ", this.params);
        }
    }
</script>

<style scoped>
    @media screen {
        h1,h2,h3,h4,h5{
            font-weight: 600;
        }

        .bold{
            font-weight:600;
        }

        .p-1{
            padding:1em;
        }

        html {
            box-sizing: border-box;
            -ms-overflow-style: scrollbar;
        }

        .pl-0 {
            padding-left: 0 !important;
        }

        .card {
            position: relative;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-direction: column;
            flex-direction: column;
            min-width: 0;
            word-wrap: break-word;
            background-clip: border-box;
            border: 1px solid rgba(0, 0, 0, 0.125);
            border-radius: 0.25rem;
        }

        .card > hr {
            margin-right: 0;
            margin-left: 0;
        }

        .card > .list-group:first-child .list-group-item:first-child {
            border-top-left-radius: 0.25rem;
            border-top-right-radius: 0.25rem;
        }

        .card > .list-group:last-child .list-group-item:last-child {
            border-bottom-right-radius: 0.25rem;
            border-bottom-left-radius: 0.25rem;
        }

        .card-body {
            -ms-flex: 1 1 auto;
            flex: 1 1 auto;
            padding: 1.25rem;
        }

        .card-body .inner-card-body{
            overflow-y:scroll;
            max-height: 37.7em;
        }

        .card-header {
            padding: 0.75rem 1.25rem;
            margin-bottom: 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.125);
        }

        .card-header:first-child {
            border-radius: calc(0.25rem - 1px) calc(0.25rem - 1px) 0 0;
        }
    }
</style>
