<template>
    <push-notes
        :apps="apps"
        :client="clientName"
        :is-host="isHost"
        @ping-for-filters="ajaxGetNoteFilters"
        @ping-users="ajaxGetNoteUsers"
        :loading-filters="loadingFilters"
        :filters="filters"
        :amt-users="amtUsers"
        :users="users"
    ></push-notes>
</template>

<script>
    import PushNotes from '../presenters/PushNotificationComponent';
    export default {
        name: "PushNotificationsContainer",
        components: { PushNotes },
        props: ['clientId', 'clientName', 'apps', 'hostUser'],
        data() {
            return {
                isHost: false,
                loadingFilters: false,
                loadingUsers: false,
                filters: '',
                amtUsers: 0,
                users: [],
                selectedApp: ''
            };
        },
        computed: {},
        methods: {
            ajaxGetNoteFilters(app_id) {
                let _this = this;
                this.loadingFilters = true;
                this.selectedApp = app_id;

                $.ajax({
                    url: `/api/client/${this.clientId}/mobile/${app_id}/notifications/push`,
                    method: 'GET',
                    dataType: 'json',
                    headers: {
                        Accept: 'application/json',
                        'Content-Type': 'application/json'
                    },
                    success(data) {
                        console.log('ajaxGetNoteFilters response', data);
                        if(data['success']) {
                            _this.filters = [];
                            for(let f in data['filters']) {
                                let valz = [
                                    {
                                        col: '',
                                        val: 'Select a filter option'
                                    }
                                ];
                                for(let g in data['filters'][f]) {
                                    let v = {
                                        col: g,
                                        val: data['filters'][f][g]
                                    };

                                    valz.push(v);
                                }
                                let fil = {
                                    name: f,
                                    vals: valz
                                };

                                _this.filters.push(fil);
                            }

                            console.log('Filter Setup', _this.filters);
                            _this.ajaxGetNoteUsers([])
                        }
                        else {
                            if(data['reason']) {
                                _this.filters = data['reason'];
                            }
                            else
                            {
                                _this.filters = 'Unknown Response from AnchorCMS Server';
                            }
                        }

                        _this.loadingFilters = false;

                    },
                    error(e) {
                        console.log('Unable to Contact Server', e);
                        _this.filters = 'Unable to Contact AnchorCMS Server';
                        _this.loadingFilters = false;
                    }
                });
            },
            ajaxGetNoteUsers(filters) {
                let _this = this;
                this.loadingUsers = true;

                let payload = {
                    filters: {}
                };

                for(let x in filters) {
                    if(filters[x] !== '') {
                        let name = this.filters[x].name;
                        payload['filters'][name] = filters[x];
                    }
                }

                $.ajax({
                    url: `/api/client/${this.clientId}/mobile/${this.selectedApp}/notifications/push/users`,
                    method: 'POST',
                    headers: {
                        Accept: 'application/json',
                        'Content-Type': 'application/json'
                    },
                    data: JSON.stringify(payload),
                    success(data) {
                        console.log('ajaxGetNoteFilters response', data);
                        if(data['success']) {
                            _this.users = data['users'];
                            _this.amtUsers = data['users'].length;
                        }
                        else {
                            _this.amtUsers = 0;
                        }

                        _this.loadingUsers = false;
                    },
                    error(e) {
                        console.log('Unable to Contact Server', e);
                        _this.amtUsers = 0;
                        _this.loadinloadingUsersgFilters = false;
                    }
                });
            }
        },
        mounted() {
            console.log('PushNotificationsContainer mounted!');
            if(this.hostUser == true) {
                console.log('PushNotificationsContainer - use is a host user!');
                this.isHost = true;
            }
        }
    }
</script>

<style scoped>

</style>
