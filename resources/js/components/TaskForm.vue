<template>
    <div>
        <div class="card-body">
            <div class="flex flex-col mb-4">
                <label class="mb-1" for="title">{{ t('tasks.title') }}:</label>
                <input class="border rounded py-2 px-2" :placeholder="t('tasks.placeholders.title')" type="text" name="title" v-model="task.title">
            </div>
            <div class="flex flex-col mb-4">
                <label class="" for="interval">{{ t('tasks.interval') }}:</label>
                <div class="flex flex-wrap -mx-2">
                    <div class="w-full md:w-1/2 lg:w-1/3 p-2"
                        v-for="(interval, index) in task_intervals" :key="index">
                        <radio-card
                            @click="setInterval"
                            selected="blue"
                            name="interval"
                            :value="index"
                            :checked="hasInterval(index)"
                            :title="(hasInterval(index) && interval.target) ? t(`tasks.${interval.label}`).replace('x', task.data[interval.target]) : t(`tasks.${interval.label}`)"
                            >
                            <div v-if="hasInterval(index) && interval.sub && interval.sub == 'optionBar'" class="w-full justify-between mt-3 flex">
                                <label v-for="(option, i) in interval.options" class="flex flex-grow justify-center border-blue border py-2" :class="{ 'border-b-4' : task.days[option], 'border-r-0': i+1 < interval.options.length }" @click="toggle(option)">
                                    <span v-html="t(`tasks.${option}`)"></span>
                                </label>
                            </div>
                            <div v-cloak v-if="hasInterval(index) && interval.sub && interval.sub == 'slider'" class="w-full justify-between mt-3 flex">
                                <vue-slider
                                    :ref="`slider-${interval.target}`"
                                    :width="'100%'"
                                    v-model="task.data[interval.target]"
                                    :min="interval.min"
                                    :max="interval.max"
                                    :piecewise="true"
                                    :piecewise-label="true"
                                    :tooltip="false"
                                    >
                                </vue-slider>
                            </div>
                        </radio-card>
                    </div>
                </div>
            </div>
            <!-- TODO: improve style of checkboxes for period, optional -->
            <div class="flex flex-col mb-4">
                <label class="mb-1" for="period">{{ t('tasks.period') }}: <input v-model="task.period" type="checkbox" name="period" id="period"></label>
                <div class="flex w-full" v-if="task.period">
                    <p class="flex flex-col w-1/2 pr-2">
                        <label for="mb-1">{{ t('tasks.start') }}</label>
                        <input class="flex border rounded py-2 px-2" :placeholder="t('tasks.placeholders.period')" type="date" name="period" v-model="task.startDate">
                    </p>
                    <p class="flex flex-col w-1/2 pl-2">
                        <label for="mb-1">{{ t('tasks.end') }}</label>
                        <input class="flex border rounded py-2 px-2" :placeholder="t('tasks.placeholders.period')" type="date" name="period" v-model="task.endDate">
                    </p>
                </div>
            </div>
            <!-- TODO: improve style of checkboxes for period, optional -->
            <div class="flex">
                <label class="mb-1 flex flex-col" for="optional">
                    <span>{{ t('tasks.optional') }}:</span>
                    <input v-model="task.optional" type="checkbox" name="optional" id="optional">
                </label>
            </div>
        </div>
        <div class="card-footer">
            <input class="button button-blue w-full rounded-t-none"
                type="submit"
                :value="t(`tasks.${method == 'post' ? 'add' : 'update'}`)"
                @click.prevent="submitForm"
                >
        </div>
    </div>
</template>

<script>
import RadioCard from './RadioCard';
import vueSlider from 'vue-slider-component';
import Task from '../Task';

export default {
    components: {
        RadioCard,
        vueSlider,
    },
    props: {
        action: String,
        taskData: Object,
        method: {
            type: String,
            default: 'post',
        },
    },
    data () {
        return {
            task_intervals: Task.intervals(),
            task: new Task(this.taskData, this.t),
        };
    },
    methods: {
        submitForm () {
            let task = this.task.export();
            axios[this.method](this.action, task)
                .then(({data}) => {
                    location.href = data.redirect;
                });
        },
        hasInterval (interval) {
            if (this.task.data && this.task.data.interval) {
                return this.task.data.interval == interval;
            }
            else {
                return this.task.interval == interval;
            }
        },
        setInterval (value) {
            this.task.interval = this.task.data.interval = value;
        },
        t (key) {
            return this.$options.filters.trans(key);
        },
        toggle (day) {
            this.task.days[day] = !this.task.days[day];
        },
    },
}
</script>
