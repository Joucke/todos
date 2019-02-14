<template>
    <div class="card-padding mb-4">
        <div class="card" @click.prevent="clickAction" :class="{'cursor-pointer': clickable}">
            <div class="card-body leading-normal" :class="colorClasses">
                <header v-if="showTitle" class="flex justify-between">
                    <h3 class="text-base" v-html="task.title"></h3>
                    <p class="" v-text="task.task_list.title" v-if="task.task_list"></p>
                </header>
                <div class="flex justify-between">
                    <p class="" v-text="task.parseInterval()"></p>
                    <p class="" v-if="task.optional" v-text="t('tasks.optional')"></p>
                </div>
                <p class="" v-if="task.starts_at">
                    <span>{{ t('tasks.start') }}</span>
                    <span v-html="moment(task.starts_at).calendar()"></span>
                    <span>{{ t('tasks.end').toLowerCase() }}</span>
                    <span v-html="moment(task.ends_at).calendar()"></span>
                </p>
                <p class="flex items-center">
                    <svg-icon name="icon-time" :class="svgClasses" class="w-6 h-6 border rounded-full mr-2"></svg-icon>
                    <span class="" v-html="task.due"></span>
                </p>
            </div>
        </div>
    </div>
</template>

<script>
import Task from '../Task';
import SvgIcon from 'vue-svgicon';
import '../icons/icon-time';

export default {
    components: {
        SvgIcon,
    },
    props: {
        taskData: Object,
        showTitle: {
            type: Boolean,
            default: false
        },
        clickable: {
            type: Boolean,
            default: false
        },
        colors: {
            type: Object,
            default: () => {
                return {
                    late: {
                        bg: 'red-lightest',
                        text: 'red-dark',
                    },
                    unscheduled: {
                        bg: 'yellow-lightest',
                        text: 'yellow-darker'
                    }
                };
            }
        },
    },
    data () {
        return {
            task: new Task(this.taskData, this.t),
        };
    },
    computed: {
        colorClasses () {
            let bgColor = 'white';
            if (this.task.late) {
                bgColor = this.colors ? this.colors.late.bg : bgColor;
            }
            if (! this.task.scheduled) {
                bgColor = this.colors ? this.colors.unscheduled.bg : bgColor;
            }
            return ['text-' + this.frontColor, 'bg-' + bgColor];
        },
        svgClasses () {
            return [
                'primary-transparent',
                'secondary-' + this.frontColor,
                'border-' + this.frontColor,
            ];
        },
        frontColor () {
            let color = 'black';
            if (this.task.late) {
                color = this.colors ? this.colors.late.text : color;
            }
            if (! this.task.scheduled) {
                color = this.colors ? this.colors.unscheduled.text : color;
            }
            return color;
        }
    },
    methods: {
        clickAction () {
            if (this.clickable) {
                window.axios.post(this.task.url)
                    .then((response) => {
                        location.reload();
                    });
            }
        },
        t (key) {
            return this.$options.filters.trans(key);
        },
        moment (when) {
            return window.moment(when);
        },
    },
}
</script>
