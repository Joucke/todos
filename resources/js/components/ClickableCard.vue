<template>
    <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 p-2">
        <div class="rounded bg-white shadow p-4 cursor-pointer"
            :class="classes"
            @click="clicked">
            <div class="flex flex-grow justify-between mb-4">
                <h3>{{ task.title }}</h3>
                <p class="text-align-right">{{ task.task_list.title }}</p>
            </div>
            <!-- TODO: clock icon -->
            <p>clock: {{ due }}</p>
        </div>
    </div>
</template>

<script>
    let moment = window.moment;
    export default {
        props: ['taskData', 'clickAction'],
        data: function () {
            return {
                task: this.taskData,
            };
        },
        methods: {
            clicked () {
                window.axios.post(this.clickAction)
                    .then((response) => {
                        location.reload();
                    });
            },
        },
        computed: {
            classes () {
                let bgColor = 'white';
                let textColor = 'black';
                if (this.late) {
                    bgColor = 'red-lightest';
                    textColor = 'red-dark'
                }
                if (! this.scheduled) {
                    bgColor = 'yellow-lightest';
                    textColor = 'yellow-darker'
                }
                return `bg-${bgColor} text-${textColor}`;
            },
            due () {
                return this.task.incompleted_scheduled_tasks.length ?
                    moment(this.task.incompleted_scheduled_tasks[0].scheduled_at).calendar() :
                    moment().add(this.task.interval, 'days').fromNow().replace('over', 'elke');
            },
            late () {
                return this.scheduled && moment().isAfter(this.task.incompleted_scheduled_tasks[0].scheduled_at);
            },
            scheduled () {
                return this.task.incompleted_scheduled_tasks.length > 0;
            },
            started () {
                return this.scheduled && !! this.task.incompleted_scheduled_tasks[0].started_at;
            },
            completed () {
                return this.scheduled && !! this.task.incompleted_scheduled_tasks[0].completed_at;
            }
        },
    }
</script>
