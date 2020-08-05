<template>
    <div class="form-group col-xs-12">
        <checkbox-grid v-if="showAbilities"
            :items="availableAbilities"
            :loading="loading"
            @item-checked="processChecked"
        ></checkbox-grid>
        <input type="hidden" name="abilities" :value="selectedAbilities" />
    </div>
</template>

<script>
    import CheckboxGrid from '../presenters/CheckboxGridComponent';
    export default {
        name: "RoleAbilitySelectContainer",
        components: {
            CheckboxGrid
        },
        props: ['mode'],
        data() {
            return {
                loading: false,
                showAbilities: false,
                selectedAbilities: [],
                availableAbilities: ''
            };
        },
        computed: {},
        methods: {
            ajaxGetAllAbilities() {
                let _this = this;
                this.loading = true;

                let client_id = $("[name='entity_id']").val();
                $.ajax({
                    url: '/abilities?client_id='+client_id,
                    method: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        console.log('Returned Data - ', data);
                        if(data['success']) {
                            _this.availableAbilities = data['abilities'];

                            for(let name in data['abilities']) {
                                let title = _this.availableAbilities[name];
                                _this.availableAbilities[title.id] = {
                                    title: title.title,
                                    id: title.id,
                                    disabled: false,
                                    checked: false
                                }
                            }

                            if(_this.mode === 'edit') {
                                _this.ajaxGetEnabledAbilities();
                            }
                            else {
                                _this.loading = false;
                            }
                        }
                        else {
                            _this.loading = false;
                        }

                        console.log('Available Abilities', _this.availableAbilities)
                    },
                    error: function (e) {
                        console.log('Error contacting server - ',e);
                        _this.loading = false;
                    }
                });
            },
            ajaxGetEnabledAbilities() {
                let _this = this;
                this.loading = true;
                let role = $("[name='name']").val();
                let client_id = $("[name='entity_id']").val();

                if (role !== '') {
                    $.ajax({
                        url: '/abilities/'+role+'?client_id='+client_id,
                        method: 'GET',
                        dataType: 'json',
                        success: function (data) {
                            console.log('Enabled Returned Data - ', data);
                            if(data['success']) {
                                for(let idx in data['assigned']) {
                                    if(data['assigned'][idx]['id'] in _this.availableAbilities) {
                                        let id = data['assigned'][idx]['id'];
                                        _this.availableAbilities[id].checked = true;
                                        _this.processChecked(_this.availableAbilities[id], id);
                                    }
                                }
                            }

                            _this.loading = false;
                        },
                        error: function (e) {
                            console.log('Error contacting server - ',e);
                            _this.loading = false;
                        }
                    });
                }
                else {
                    console.log('skipping enabled abilities.');
                }

            },
            processChecked(item, name) {
                console.log('Item Toggled - '+name, item);
                this.availableAbilities[name] = item;

                if(item.checked) {
                    this.selectedAbilities.push(item.id);
                }
                else {
                    for(let idx in this.selectedAbilities) {
                        if(this.selectedAbilities[idx] === item.id) {
                            this.selectedAbilities.splice(idx, 1);
                        }
                    }
                }

                console.log('Updated selectedAbilities', this.selectedAbilities);
            },
        },
        mounted() {
            let _this = this;
            $("[name='entity_id']").change(function () {
                _this.ajaxGetAllAbilities();
                _this.showAbilities = true;
            });

            let role = $("[name='name']").val();
            let client_id = $("[name='entity_id']").val();

            if((client_id !== '')) {
                _this.ajaxGetAllAbilities();
                _this.showAbilities = true;
            }

            console.log('RoleAbilitySelectContainer mounted!', client_id);
        }
    }
</script>

<style scoped>

</style>
