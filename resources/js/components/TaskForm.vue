<template>
    <div>
        <div class="card-header bg-grey-lighter border-b">
            <p class="font-semibold">{{ formTitle }}</p>
        </div>

        <div class="card-body">
            <div class="flex flex-col mb-4">
                <input
                    class="border rounded p-2"
                    :placeholder="t('tasks.placeholders.title')"
                    type="text"
                    name="title"
                    autofocus
                    v-model="task.title">

                <p class="text-red text-sm my-2" role="alert" v-if="errors.title">
                    <strong v-for="error in errors.title" v-text="error"></strong>
                </p>
            </div>
            <div class="flex flex-col mb-4">
                <label class="" for="interval">{{ t('tasks.interval') }}:</label>
                <div class="flex flex-wrap -mx-2">
                    <div class="w-full md:w-1/2 lg:w-1/3 p-2"
                        v-for="(interval, index) in task_intervals" :key="index">
                        <radio-card
                            @selecting="setInterval"
                            selected="blue"
                            name="interval"
                            :value="index"
                            :checked="hasInterval(index)"
                            :title="titleFor(interval)"
                            >
                            <div v-if="hasInterval(index) && interval.sub && interval.sub == 'optionBar'" class="w-full justify-between mt-3 flex">
                                <label v-for="(option, i) in interval.options" class="flex relative flex-grow justify-center border-blue border py-2" :class="{ 'border-b-4' : task.days[option], 'border-r-0': i+1 < interval.options.length }" @click="toggle(option)" :for="`option_${i}`">
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
            <div class="flex flex-col mb-4">
                <check-box class="mb-1" :checked="task.period" @toggle="task.period = !task.period" :label="t('tasks.period')">
                </check-box>
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
            <div class="flex">
                <check-box class="mb-1" :checked="task.optional" @toggle="task.optional = !task.optional" :label="t('tasks.optional')">
                </check-box>
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
import CheckBox from './CheckBox';
import RadioCard from './RadioCard';
import vueSlider from 'vue-slider-component';
import Task from '../Task';

export default {
    components: {
        CheckBox,
        RadioCard,
        vueSlider,
    },
    props: {
        action: String,
        formTitle: String,
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
            errors: {
                title: null,
            },
        };
    },
    methods: {
        submitForm () {
            let task = this.task.export();
            this.errors = {title: null};
            axios[this.method](this.action, task)
                .then(({data}) => {
                    location.href = data.redirect;
                })
                .catch(({response}) => {
                    this.errors = response.data.errors;
                });
        },
        hasInterval (interval) {
            if (this.task.data && this.task.data.interval) {
                return this.task.data.interval == interval;
            }
            return this.task.interval == interval;
        },
        setInterval (value) {
            this.task.interval = this.task.data.interval = value;
            this.$forceUpdate();
        },
        t (key) {
            return this.$options.filters.trans(key);
        },
        titleFor (interval) {
            if (interval.target) {
                return this.task.parseTarget(interval);
            }
            return this.t(`tasks.${interval.label}`);
        },
        toggle (day) {
            this.task.days[day] = !this.task.days[day];
        },
    },
}
</script>
