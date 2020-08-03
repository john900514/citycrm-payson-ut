<template>
    <div class="stuff">
        <input
            :type="type"
            :name="name"
            :value="imgUrl"
            class="form-control"
            @click="pickFile"
        >

        <input type='file' ref="file" v-on:change="postUpload()" id="filer"/>
        <input type="hidden" v-model="imgPath" />

        <div class="vld-parent">
            <loading :active.sync="IsLoading"
                     :can-cancel="true"

                     :color="'#35A2FE'"
                     :loader="'bars'"
                     :is-full-page="true"
            ></loading>
        </div>
    </div>
</template>

<script>
    import Loading from 'vue-loading-overlay';
    import 'vue-loading-overlay/dist/vue-loading.css';

    export default {
        name: "UploadFileToURL",
        props: ['type', 'name', 'value'],
        components: {
            Loading
        },
        watch: {
            file($localPath) {
                let _this = this;
                this.loading = true;
                let formData = new FormData();
                formData.append('file', this.file);
                axios.post( 'image-upload',
                    formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }
                ).then(function(response){
                    let data = response.data;
                    if(('success' in data)) {
                        if(data.success) {
                            _this.imgUrl = data.url
                        }
                        else {
                            alert(data.reason);
                        }
                    }
                    else {
                        alert('Your request failed. Try again.');
                    }

                    _this.loading = false;
                })
                    .catch(function(e){
                        console.log('FAILURE!!', e);
                        alert('Something went wrong, please try again');
                        _this.loading = false;
                    });
            }
        },
        data() {
            return {
                imgUrl: '',
                imgPath: '',
                file: '',
                loading: false,
            };
        },
        computed: {
            IsLoading() {
                return this.loading;
            },
        },
        methods: {
            pickFile() {
                $('#filer').click();
            },
            postUpload() {
                this.file = this.$refs.file.files[0];
            }
        },
        mounted() {
            this.imgUrl = this.value
        }
    }
</script>

<style scoped>
    #filer {
        margin: 1em 0;
        display: none;
    }
</style>
