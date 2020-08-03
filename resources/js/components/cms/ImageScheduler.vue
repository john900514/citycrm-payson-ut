<template>
    <div class="stuff">
        <div class="radio-group-section">
            <div class="radio-group">
                <input type="radio" name="scheduleEnabled" v-model="scheduleMode" :value="false">
                <label>I want this image always showing</label>
            </div>
            <div class="radio-group">
                <input type="radio" name="scheduleEnabled" v-model="scheduleMode" :value="true">
                <label>I want this image to show at a certain time</label>
            </div>
        </div>

        <div class="calendar-section" v-if="scheduleMode === true">
            <b-form-input class="schedule-dates" type="text" onfocus="(this.type='datetime-local')" v-model="scheduleStart" placeholder="Start Date" name="schedule_start"></b-form-input>
            <b-form-input class="schedule-dates" type="text" onfocus="(this.type='datetime-local')" v-model="scheduleEnd" placeholder="End Date" name="schedule_end"></b-form-input>
        </div>
    </div>
</template>

<script>
    export default {
        name: "ImageScheduler",
        props: ['startDate','endDate'],
        watch: {
            scheduleMode(flag) {
                if(flag === 'true') {
                    $('.calendar-section').fadeIn();
                }
                else {
                    $('.calendar-section').fadeOut();
                }

            }
        },
        data() {
            return {
                scheduleMode: false,
                scheduleStart: '',
                scheduleStartTime: '',
                scheduleEnd: null,
                scheduleEndTime: ''
            };
        },
        mounted() {
            this.scheduleStart = this.startDate;
            this.scheduleEnd = this.endDate;

            if(this.startDate === '' || this.startDate === undefined || this.startDate === null) {
                $('.calendar-section').hide();
            }
            else {
                this.scheduleMode = 'true';
                $('.calendar-section').show();
            }
            console.log('Scheduler Mounted!')
        }
    }
</script>

<style scoped>
    .radio-group {
        margin: 0.5em 0;
    }

    .schedule-dates {
        margin: 1em 0;
    }
</style>
