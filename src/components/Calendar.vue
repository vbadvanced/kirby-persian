<script>
import { dayjs } from '../helpers';

function initState(value) {

    const current = value ? dayjs.bind(this)(value) : dayjs.bind(this)();

    return {
      day: current.date(),
      month: current.month(),
      year: current.year(),
      today: dayjs.bind(this)(),
      current: current,
    };
}

export default {
  extends: 'k-calendar',
  props: {
    jalali: Boolean
  },
  data() {
    return initState.bind(this)(this.value);
  },
  computed: {
    date() {
      return dayjs.bind(this)(`${this.year}-${this.month + 1}-${this.day}`, { jalali: this.jalali });
    },
    weekdays() {
      return this.jalali ? [
        this.$t('days.short.sat'),
        this.$t('days.short.sun'),
        this.$t('days.short.mon'),
        this.$t('days.short.tue'),
        this.$t('days.short.wed'),
        this.$t('days.short.thu'),
        this.$t('days.short.fri'),
      ] : [
        this.$t('days.mon'),
        this.$t('days.tue'),
        this.$t('days.wed'),
        this.$t('days.thu'),
        this.$t('days.fri'),
        this.$t('days.sat'),
        this.$t('days.sun'),
      ];
    },
    monthnames() {
      return this.jalali ? [
        this.$t('jmonths.farvardin'),
        this.$t('jmonths.ordibehest'),
        this.$t('jmonths.khordad'),
        this.$t('jmonths.tir'),
        this.$t('jmonths.mordad'),
        this.$t('jmonths.shahrivar'),
        this.$t('jmonths.mehr'),
        this.$t('jmonths.aban'),
        this.$t('jmonths.azar'),
        this.$t('jmonths.dey'),
        this.$t('jmonths.bahman'),
        this.$t('jmonths.esfand'),
      ] : [
        this.$t('months.january'),
        this.$t('months.february'),
        this.$t('months.march'),
        this.$t('months.april'),
        this.$t('months.may'),
        this.$t('months.june'),
        this.$t('months.july'),
        this.$t('months.august'),
        this.$t('months.september'),
        this.$t('months.october'),
        this.$t('months.november'),
        this.$t('months.december'),
      ];
    },
  },
  watch: {
    jalali(jalali) {
      Object.assign(this.$data, initState.bind(this)(this.value));
    },
    value(value) {
      Object.assign(this.$data, initState.bind(this)(value));
    }
  },
  methods: {
    selectToday() {
      this.set(dayjs.bind(this)());
      this.select(this.day);
    },
    select(day) {

      if (day) {
        this.day = day;
      }

      const date = dayjs.bind(this)(`${this.year}-${this.month + 1}-${this.day} ${this.$helper.pad(this.current.hour())}:${this.$helper.pad(this.current.minute())}`, { jalali: this.jalali });

      this.$emit("input", date.toISOString());
    }
  }
};
</script>