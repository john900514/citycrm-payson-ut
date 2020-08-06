<template>
    <div class="container">
        <!-- Observe the inner div for margins -->
        <div class="inner-container">
            <div class="row">
                <!-- Observe the inner div for margins -->
                <div class="inner-row">
                    <list-widget
                        title="General Info"
                        :params="basicDetails"
                    ></list-widget>

                    <list-widget v-if="urlParams !== ''"
                        title="Page Visited"
                        :subtitle="getPageName"
                        :params="urlParams"
                    ></list-widget>

                    <list-widget v-if="serverRequest !== ''"
                                 title="Visitor Info"
                                 subtitle="Detected Fresh From the Server"
                                 :params="serverRequest"
                    ></list-widget>
                </div>
            </div>

            <div class="row">
                <!-- Observe the inner div for margins -->
                <div class="inner-row">
                    <mappy-list-widget v-if="locationInfo !== ''"
                        title="Visitor Location"
                        subtitle="Mappy info!"
                        :params="locationInfo"
                        :gps="locationGps"
                        :google-maps-key="googleMapsKey"
                    ></mappy-list-widget>


                    <list-widget v-if="visitorActivity !== ''"
                        title="Activity Analysis"
                        subtitle="IP-Tracked History w/ links"
                        :params="visitorActivity"
                    ></list-widget>
                    <list-widget v-else
                                 title="Activity Analysis"
                                 subtitle="IP-Tracked History w/ links"
                                 params="loading"
                    ></list-widget>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import MappyListWidget from './ListWidgetWithMap';

    export default {
        name: "activityScreenComponent",
        components: {
          MappyListWidget
        },
        props: [
            'basicDetails','urlParams', 'serverRequest',
            'visitorActivity', 'locationInfo', 'locationGps',
            'googleMapsKey'
        ],
        computed: {
            getPageName() {
                let result = 'Unknown';

                if(this.serverRequest !== '') {
                    if('Page Requested' in this.serverRequest) {
                        result = this.serverRequest['Page Requested']
                    }
                }

                return result;
            }
        },
        mounted() {
            console.log('Activity Screen Presenter Component Mounted!')
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

        .m-1{
            margin:.25em;
        }
        .p-1{
            padding:1em;
        }

        html {
            box-sizing: border-box;
            -ms-overflow-style: scrollbar;
        }

        .container {

        }

        .pl-0 {
            padding-left: 0 !important;
        }

        .container-fluid {
            width: 100%;
            padding-right: 15px;
            padding-left: 15px;
            margin-right: auto;
            margin-left: auto;
        }
        .col-1, .col-2, .col-3, .col-4, .col-5, .col-6, .col-7, .col-8, .col-9, .col-10, .col-11, .col-12, .col,
        .col-auto, .col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12, .col-sm,
        .col-sm-auto, .col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12, .col-md,
        .col-md-auto, .col-lg-1, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6, .col-lg-7, .col-lg-8, .col-lg-9, .col-lg-10, .col-lg-11, .col-lg-12, .col-lg,
        .col-lg-auto, .col-xl-1, .col-xl-2, .col-xl-3, .col-xl-4, .col-xl-5, .col-xl-6, .col-xl-7, .col-xl-8, .col-xl-9, .col-xl-10, .col-xl-11, .col-xl-12, .col-xl,
        .col-xl-auto {
            position: relative;
            width: 100%;
            padding-right: 15px;
            padding-left: 15px;
        }

        .col {
            -ms-flex-preferred-size: 0;
            flex-basis: 0;
            -ms-flex-positive: 1;
            flex-grow: 1;
            max-width: 100%;
        }

        .col-auto {
            -ms-flex: 0 0 auto;
            flex: 0 0 auto;
            width: auto;
            max-width: 100%;
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

        .card-header {
            padding: 0.75rem 1.25rem;
            margin-bottom: 0;
            background-color: rgba(0, 0, 0, 0.03) !important;
            border-bottom: 1px solid rgba(0, 0, 0, 0.125);
        }

        .card-header:first-child {
            border-radius: calc(0.25rem - 1px) calc(0.25rem - 1px) 0 0;
        }
    }

    @media screen {
        .row {
            flex-wrap: unset !important;
            display: unset !important;
        }
        .container {
            width: 100%;
            height: 100%;
            margin: 0 !important;
            padding: 0 !important;
        }

        .inner-container {
            display: flex;
            justify-content: center;
        }

        .inner-container .row {
            margin: 0;
        }

        .inner-container .inner-row {
            display: flex;
            flex-flow: column;
        }
    }

    @media screen and (max-width: 999px){
        .inner-container {
            flex-flow: column;
        }

        .inner-container .row {
            width: 100%;
        }
    }

    @media screen and (min-width: 1000px) {
        .inner-container {
            flex-flow: row wrap;
        }

        .inner-container .row {
            width: 50%;
        }

        .inner-container .inner-row {
            display: flex;
            flex-flow: column;
        }
    }
</style>
