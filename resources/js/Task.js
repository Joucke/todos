class Task {
    static days () {
        return {
            mon: false,
            tue: false,
            wed: false,
            thu: false,
            fri: false,
            sat: false,
            sun: false
        };
    }
    static intervals () {
        return {
            1: {label: 'daily'},
            2: {label: 'other_day'},
            7: {label: 'weekly'},
            14: {label: 'other_week'},
            30: {label: 'monthly'},
            60: {label: 'other_month'},
            77: {
                label: 'weekly_on',
                sub: 'optionBar',
                options: ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'],
            },
            88: {
                label: 'many_weekly',
                sub: 'slider',
                min: 1,
                max: 6,
                target: 'weeks',
            },
            99: {
                label: 'many_monthly',
                sub: 'slider',
                min: 1,
                max: 6,
                target: 'months',
            },
        };
    }
    constructor (taskData, translator) {
        this.t = translator;
        this.import(taskData);
    }
    get due () {
        return this.scheduled
            ? moment(this.first_scheduled.scheduled_at).calendar()
            : moment().add(this.interval, 'days').fromNow();
    }
    get endDate () {
        return moment(this.ends_at).format('YYYY-MM-DD');
    }
    set endDate (value) {
        this.ends_at = moment(value).format('YYYY-MM-DD HH:mm:ss');
    }
    get first_scheduled () {
        let s;
        [s] = this.scheduled ? this.incompleted_scheduled_tasks : [null];
        return s;
    }
    get late () {
        return this.scheduled
            && moment().isAfter(this.first_scheduled.scheduled_at);
    }
    get scheduled () {
        return this.incompleted_scheduled_tasks
            && this.incompleted_scheduled_tasks.length > 0;
    }
    get startDate () {
        return moment(this.starts_at).format('YYYY-MM-DD');
    }
    set startDate (value) {
        this.starts_at = moment(value).format('YYYY-MM-DD HH:mm:ss');
    }
    export () {
        return {
            title: this.title,
            interval: this.calculateInterval(),
            starts_at: this.period ? this.starts_at : null,
            ends_at: this.period ? this.ends_at : null,
            days: this.interval == 77 ? this.days : null,
            optional: this.optional,
            data: {
                interval: this.interval,
                weeks: this.interval == 88 ? this.weeks : null,
                months: this.interval == 99 ? this.months : null,
            },
        };
    }
    import (data) {
        Object.assign(this, data);
        this.days = this.days || Task.days();
        this.period = !! this.starts_at && !! this.ends_at;
        this.data = this.data || {};
        this.data.weeks = this.data.weeks || 3;
        this.data.months = this.data.months || 3;
        this.optional = this.optional || false;
    }
    calculateInterval () {
        switch (this.interval) {
            case 99: // every x months
                return 30 * this.data.months;
                break;
            case 88: // every x weeks
                return 7 * this.data.weeks;
                break;
            case 77: // weekly on multiple days
                return 7;
                break;
            default:
                return this.interval;
                break;
        }
    }
    parseInterval () {
        let interval = (this.data && this.data.interval)
            ? this.data.interval
            : this.interval;
        let task = Task.intervals()[interval];
        let days = [];
        if (task.options) {
            task.options.map((option) => {
                if (this.days[option]) {
                    days.push(this.t(`tasks.${option}`));
                }
            });
            return this.t(`tasks.${task.label}`) + ' ' + days.join(', ');
        }
        if (task.target) {
            return this.t(`tasks.${task.label}`).replace('x', this.data[task.target]);
        }
        return this.t(`tasks.${task.label}`);
    }
}

export default Task;
