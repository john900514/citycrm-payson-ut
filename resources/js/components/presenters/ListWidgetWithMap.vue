<template>
    <div class="col-12 p-1">
        <div class="card">
            <div class="card-body map-area">
                <div class="col-xs-12">
                    <iframe :width="iFrameWidth" v-if="showMap"
                            height="300"
                            frameborder="0"
                            style="border:0;width: 100%;"
                            :src="'https://www.google.com/maps/embed/v1/view?key='+googleMapsKey+'&zoom=18&center='+gps['lat']+','+gps['long']"
                            allowfullscreen="false">
                    </iframe>
                </div>
            </div>

            <div class="card-header">
                <h4 style="margin-bottom:0;">{{ title }}</h4><small>{{ subtitle }}</small>
            </div>
            <div class="card-body">
                <div class="col-xs-12">
                    <ul class="pl-0" v-if="(params !== 'empty') && (params !== 'loading')">
                        <li v-for="(idx, key) in params">
                            <span class="bold">{{key}}:&nbsp;</span><span>{{ idx }}</span>
                        </li>
                    </ul>
                    <p v-else-if="params === 'loading'">Loading...<i class="fad fa-spinner-third fa-spin"></i></p>
                    <p v-else>No Map Data Passed In.</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "ListWidgetWithMap",
        props: ['title', 'subtitle', 'params', 'gps', 'googleMapsKey'],
        watch: {
            gps(coordinates) {
                console.log('Map Watcher - '+coordinates);
                this.lat = coordinates.lat;
                this.long = coordinates.long;
            }
        },
        data() {
            return {
                ipAddress: '',
                lat: '',
                long:'',
                frameWidth: 0,
            };
        },
        computed: {
            showMap() {
                let show = false;

                if((this.lat !== '') && (this.long !== '')) {
                    show = true;
                }

                return show;
            },
            // appendBreadcrumb(){
            //     let _href = $('#view-more-breadcrumb').attr("href");
            // $('#view-more-breadcrumb').attr("href", _href + '?ip='+this.ip)
            // },
            iFrameWidth() {
                console.log('Stuffs - '+ $('.card .col-x-12').innerWidth() );
                if(this.frameWidth !== $('.card').innerWidth()) {
                    this.frameWidth = $('.card').innerWidth();
                }

                return this.frameWidth;
            },

        },
        methods: {
            resizeIframe() {
                console.log('Frame size changed');
                this.frameWidth = $('.card').innerWidth();
                return;
            },
        },
        mounted() {
            console.log('Mappy List Widget Loaded! IP - '+this.ip);
            window.addEventListener("resize", this.resizeIframe());
            this.ipAddress = this.ip;

            /*
            $("a#view-more-breadcrumb").attr("href", function(i, href) {
                return href + '?ip='+this.ip;
            });
             */
        },

        created: function(){

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

        .map-area .col-xs-12 {
            padding: 0;
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
