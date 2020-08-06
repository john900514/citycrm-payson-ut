<template>
    <activity-screen
        :basic-details="basicDetails"
        :url-params="urlParams"
        :server-request="serverRequest"
        :visitor-activity="visitorActivity"
        :location-info="locationActivity"
        :location-gps="locationGps"
        :google-maps-key="mapsApiKey"
    ></activity-screen>
</template>

<script>
    import ActivityScreen from '../presenters/activityScreenComponent'
    export default {
        name: "VisitorActivityDataComponent",
        components: {
            ActivityScreen
        },
        props: ['record', 'apiUrl', 'mapsApiKey'],
        watch: {
            ipAddress($ip) {
                console.log('Located IP Address '+$ip+' pinging the server for activity!...')
                this.ajaxGetIpAddressInfo($ip);
                this.ajaxGetLocationInfo($ip);
            }
        },
        data() {
            return {
                basicDetails: '',
                urlParams: '',
                serverRequest: '',
                visitorActivity: '',
                locationActivity: '',
                locationGps: '',
                ipAddress: ''
            };
        },
        methods: {
            init() {
                if(this.record !== '') {
                    if('url_parameters' in this.record) {
                        if(((Array.isArray(this.record['url_parameters'])) && (this.record['url_parameters'].length === 0))) {
                            this.urlParams = 'empty'
                        }
                        else {
                            this.urlParams = this.record['url_parameters']
                        }
                    }

                    if('misc_info' in this.record) {
                        this.serverRequest = {};
                        for(let key in this.record['misc_info']) {
                            switch(key) {
                                case 'REMOTE_ADDR':
                                    this.serverRequest['IP Address'] = this.record['misc_info'][key];
                                    this.ipAddress = this.record['misc_info'][key];
                                    break;

                                case 'HTTP_USER_AGENT':
                                    this.serverRequest['Browser'] = this.record['misc_info'][key];
                                    break;

                                case 'DOCUMENT_URI':
                                    this.serverRequest['Page Requested'] = this.record['misc_info'][key];
                                    break;

                                case 'REQUEST_URI':
                                    this.serverRequest['Uri'] = this.record['misc_info'][key];
                                    break;

                                case 'QUERY_STRING':
                                    this.serverRequest['GET Params'] = this.record['misc_info'][key];
                                    break;

                                default:
                                    console.log('Skipping '+key)
                                    //this.serverRequest[key] = this.record['server_info'][key];
                            }
                        }
                    }
                }

                this.basicDetails = this.populateBasicDetails(this.record);
            },
            populateBasicDetails(data) {
                let results = {};

                for(let key in data) {
                    switch(key) {

                        case 'id':
                            results['ID'] = data[key];
                            break;

                        case 'activity_type':
                            results['Activity Type'] = data[key];
                            break;

                        case 'campaign':
                            results['Campaign'] = data[key];
                            break;

                        case 'created_at':
                            results['Time & Date'] = data[key];
                            break;

                        case 'lead_uuid':
                            if(data[key] !== null ) {
                                results['Lead Record ID'] = '<a href="/cms/leads/'+data[key]+'">'+data[key]+'</a>';
                            }
                            break;

                        case 'referral_uuid':
                            if(data[key] !== null ) {
                                results['Referral Record ID'] = '<a href="/cms/referrals/'+data[key]+'">'+data[key]+'</a>';
                            }
                            break;

                        case 'conversion_uuid':
                            if(data[key] !== null ) {
                                results['Conversion Record ID'] = '<a href="/cms/member-conversions/'+data[key]+'">'+data[key]+'</a>';
                            }
                            break;

                        default:
                            console.log('Skipping column - '+key);
                    }
                }

                return results;
            },
            ajaxGetLocationInfo(ip) {
                let _this = this;
                this.locationActivity = 'loading';

                let payload = {
                    ip: ip
                };

                $.ajax({
                    url: _this.apiUrl+'/locations/ip',
                    method: "POST",
                    data: payload,
                    success: function (data) {
                        console.log('Visitor Location response -', data);
                        if(('success' in data) && (data['success'] === true)) {
                            _this.locationActivity = data['info'];
                            _this.locationGps = data['coordinates'];
                        }
                        else {
                            _this.locationActivity = 'empty';
                        }
                    },
                    error: function(e) {
                        console.log(e);
                        _this.locationActivity = 'empty';
                    }
                })
            },
            ajaxGetIpAddressInfo($ip) {
                let _this = this;

                $.ajax({
                    url: _this.apiUrl+'/visitors/'+$ip,
                    method: "GET",
                    success: function (data) {
                        console.log('Visitor activity response -', data);
                        if(('success' in data) && (data['success'] === true)) {
                            _this.processVisitorActivity(data['activity']);
                        }
                        else {
                            _this.visitorActivity = 'empty';
                        }
                    },
                    error: function(e) {
                        console.log(e);
                        _this.visitorActivity = 'empty';
                    }
                });
            },
            processVisitorActivity(activity) {
                let results = {};

                for(let key in activity) {
                    switch(key) {
                        case 'total':
                            results['Times Visited'] = activity[key];
                            break;

                        case  'daily':
                            results['Daily'] = '';
                            for(let day in activity[key]) {
                                results['On '+activity[key][day]['logged']] = activity[key][day].total;
                            }
                            break;

                        case 'history':
                            let tick = 1;
                            results['History'] = '';
                            for(let history in activity[key]) {
                                if(activity[key][history]['id'] === this.record.id) {
                                    results['Visit '+tick] = '<span>'+activity[key][history]['created_at']+' - You are here.</span>';
                                }
                                else {
                                    if(activity[key][history]['type'] == 'archive') {
                                        results['Visit '+tick] = '<a href="/crud-visitors/'+activity[key][history]['id']+'/view-more">'+activity[key][history]['created_at']+'</a>';
                                    }
                                    else {
                                        results['Visit '+tick] = '<a href="/crud-visitors/'+activity[key][history]['id']+'/view-more">'+activity[key][history]['created_at']+'</a>';
                                    }

                                }

                                tick++;
                            }
                            break;

                        default:
                            results[key] = activity[key];
                    }
                }

                this.visitorActivity = results;
            }
        },
        mounted() {
            this.init();
            console.log('Activity Screen Container Component Mounted!', this.record);
        }
    }
</script>

<style scoped>

</style>
