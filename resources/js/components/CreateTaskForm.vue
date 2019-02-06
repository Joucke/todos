<template>
    <div>
        <div class="flex flex-col mb-4">
            <label class="mb-1" for="title">{{ t('tasks.title') }}:</label>
            <input class="border rounded py-2 px-2" :placeholder="t('tasks.placeholders.title')" type="text" name="title" v-model="task.title">
        </div>
        <div class="flex flex-col mb-4">
            <label class="" for="interval">{{ t('tasks.interval') }}:</label>
            <div class="flex flex-wrap -mx-2">
                <div class="w-full md:w-1/2 lg:w-1/3 p-2">
                    <radio-card
                        @click="setInterval"
                        selected="blue"
                        name="interval"
                        value="1"
                        :checked="task.interval === 1"
                        :title="t('tasks.daily')"
                        >
                    </radio-card>
                </div>
                <div class="w-full md:w-1/2 lg:w-1/3 p-2">
                    <radio-card
                        @click="setInterval"
                        selected="blue"
                        name="interval"
                        value="2"
                        :checked="task.interval === 2"
                        :title="t('tasks.other_day')"
                        >
                    </radio-card>
                </div>
                <div class="w-full md:w-1/2 lg:w-1/3 p-2">
                    <radio-card
                        @click="setInterval"
                        selected="blue"
                        name="interval"
                        value="7"
                        :checked="task.interval === 7"
                        :title="t('tasks.weekly')"
                        >
                    </radio-card>
                </div>
                <div class="w-full md:w-1/2 lg:w-1/3 p-2">
                    <radio-card
                        @click="setInterval"
                        selected="blue"
                        name="interval"
                        value="14"
                        :checked="task.interval === 14"
                        :title="t('tasks.other_week')"
                        >
                    </radio-card>
                </div>
                <div class="w-full md:w-1/2 lg:w-1/3 p-2">
                    <radio-card
                        @click="setInterval"
                        selected="blue"
                        name="interval"
                        value="30"
                        :checked="task.interval === 30"
                        :title="t('tasks.monthly')"
                        >
                    </radio-card>
                </div>
                <div class="w-full md:w-1/2 lg:w-1/3 p-2">
                    <radio-card
                        @click="setInterval"
                        selected="blue"
                        name="interval"
                        value="60"
                        :checked="task.interval === 60"
                        :title="t('tasks.other_month')"
                        >
                    </radio-card>
                </div>
                <div class="w-full md:w-1/2 lg:w-1/3 p-2">
                    <radio-card
                        @click="setInterval"
                        selected="blue"
                        name="interval"
                        value="77"
                        :checked="task.interval === 77"
                        :title="t('tasks.weekly_on')"
                        >
                        <div class="w-full justify-between mt-3" :class="{ hidden: task.interval != 77, flex: task.interval === 77 }">
                            <label class="flex flex-grow justify-center border-blue border border-r-0 py-2" :class="{ 'border-b-4' : task.days.mon }" for="mon" @click="toggle('mon')">
                                <span v-html="t('tasks.mon')"></span>
                            </label>
                            <label class="flex flex-grow justify-center border-blue border border-r-0 py-2" :class="{ 'border-b-4' : task.days.tue }" for="tue" @click="toggle('tue')">
                                <span v-html="t('tasks.tue')"></span>
                            </label>
                            <label class="flex flex-grow justify-center border-blue border border-r-0 py-2" :class="{ 'border-b-4' : task.days.wed }" for="wed" @click="toggle('wed')">
                                <span v-html="t('tasks.wed')"></span>
                            </label>
                            <label class="flex flex-grow justify-center border-blue border border-r-0 py-2" :class="{ 'border-b-4' : task.days.thu }" for="thu" @click="toggle('thu')">
                                <span v-html="t('tasks.thu')"></span>
                            </label>
                            <label class="flex flex-grow justify-center border-blue border border-r-0 py-2" :class="{ 'border-b-4' : task.days.fri }" for="fri" @click="toggle('fri')">
                                <span v-html="t('tasks.fri')"></span>
                            </label>
                            <label class="flex flex-grow justify-center border-blue border border-r-0 py-2" :class="{ 'border-b-4' : task.days.sat }" for="sat" @click="toggle('sat')">
                                <span v-html="t('tasks.sat')"></span>
                            </label>
                            <label class="flex flex-grow justify-center border-blue border py-2" :class="{ 'border-b-4' : task.days.sun }" for="sun" @click="toggle('sun')">
                                <span v-html="t('tasks.sun')"></span>
                            </label>
                        </div>
                    </radio-card>
                </div>
                <div class="w-full md:w-1/2 lg:w-1/3 p-2">
                    <radio-card
                        @click="setInterval"
                        selected="blue"
                        name="interval"
                        value="88"
                        :checked="task.interval === 88"
                        :title="task.interval === 88 ? t('tasks.many_weekly').replace('x', task.weeks) : t('tasks.many_weekly')"
                        >
                        <div class="w-full justify-between mt-3" :class="{ hidden: task.interval != 88, flex: task.interval === 88 }">
                            <vue-slider
                                ref="slider-weeks"
                                :width="'100%'"
                                v-model="task.weeks"
                                :min="1"
                                :max="6"
                                :piecewise="true"
                                :piecewise-label="true"
                                :tooltip="false"
                                >
                            </vue-slider>
                        </div>
                    </radio-card>
                </div>
                <div class="w-full md:w-1/2 lg:w-1/3 p-2">
                    <radio-card
                        @click="setInterval"
                        selected="blue"
                        name="interval"
                        value="99"
                        :checked="task.interval === 99"
                        :title="task.interval === 99 ? t('tasks.many_monthly').replace('x', task.months) : t('tasks.many_monthly')"
                        >
                        <div class="w-full justify-between mt-3" :class="{ hidden: task.interval != 99, flex: task.interval === 99 }">
                            <vue-slider
                                ref="slider-months"
                                :width="'100%'"
                                v-model="task.months"
                                :min="1"
                                :max="6"
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
            <label class="mb-1" for="period">{{ t('tasks.period') }}: <input v-model="task.period" type="checkbox" name="period" id="period"></label>
            <div class="flex w-full" v-if="task.period">
                <p class="flex flex-col w-1/2">
                    <label for="mb-1">{{ t('tasks.start') }}</label>
                    <input class="flex border rounded py-2 px-2" :placeholder="t('tasks.placeholders.period')" type="date" name="period" v-model="task.starts_at">
                </p>
                <p class="flex flex-col w-1/2">
                    <label for="mb-1">{{ t('tasks.end') }}</label>
                    <input class="flex border rounded py-2 px-2" :placeholder="t('tasks.placeholders.period')" type="date" name="period" v-model="task.ends_at">
                </p>
            </div>
        </div>
        <div class="flex mb-4">
            <label class="mb-1 flex flex-col" for="optional">
                <span>{{ t('tasks.optional') }}:</span>
                <input v-model="task.optional" type="checkbox" name="optional" id="optional">
            </label>
        </div>
        <p class="">
            <input class="bg-blue hover:bg-blue-dark text-white no-underline py-2 px-4 rounded"
                type="submit"
                :value="t('tasks.add')"
                @click.prevent="createTask"
                >
        </p>
    </div>
</template>

<script>
import RadioCard from './RadioCard';
import vueSlider from 'vue-slider-component';

export default {
    components: {
        RadioCard,
        vueSlider,
    },
    props: ['action'],
    data () {
        return {
            task: {
                title: '',
                interval: 1,
                days: {
                    mon: false,
                    tue: false,
                    wed: false,
                    thu: false,
                    fri: false,
                    sat: false,
                    sun: false,
                },
                weeks: 3,
                months: 3,
                period: false,
                starts_at: window.moment().format('YYYY-MM-DD'),
                ends_at: window.moment().add(6, 'months').format('YYYY-MM-DD'),
                optional: false,
            }
        };
    },
    methods: {
        createTask () {
            let task = {
                title: this.task.title,
                interval: this.interval(),
                starts_at: this.task.period ? this.task.starts_at : null,
                ends_at: this.task.period ? this.task.ends_at : null,
                days: this.task.interval === 77 ? this.task.days : null,
                optional: this.task.optional,
                data: {
                    interval: this.task.interval,
                },
            };
            axios.post(this.action, task)
                .then(({data}) => {
                    location.href = data.redirect;
                });
        },
        interval () {
            switch (this.task.interval) {
                case 99:
                    // every x months
                    return 30 * this.task.months;
                    break;
                case 88:
                    // every x weeks
                    return 7 * this.task.weeks;
                    break;
                case 77:
                    // weekly on multiple days
                    return 7;
                    break;
                default:
                    return this.task.interval;
            }
        },
        refreshSliders () {
            this.$nextTick(() => {
                this.$refs['slider-weeks'].refresh();
                this.$refs['slider-months'].refresh();
            });
        },
        setInterval (value) {
            this.task.interval = parseInt(value);
            this.refreshSliders();
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
