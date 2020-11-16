<script>
import { dayjs } from '../../helpers';

function initState() {
    return {
      date: dayjs.bind(this)(this.value),
      minDate: this.calculate(this.min, "min"),
      maxDate: this.calculate(this.max, "max")
    };
}

export default {
  extends: 'k-date-input',
  props: {
    jalali: Boolean
  },
  data() {
    return initState.bind(this)();
  },
  watch: {
    jalali(jalali) {
      Object.assign(this.$data, initState.bind(this)());
    },
    value(value) {
      this.date = dayjs.bind(this)(value);
    }
  },
  methods: {
    calculate(value, what) {
      const calc = {
        min: {run: "subtract", take: "startOf"},
        max: {run: "add", take: "endOf" },
      }[what];

      let date = value ? dayjs.bind(this)(value) : null;
      if (!date || date.isValid() === false) {
        date = dayjs.bind(this)()[calc.run](10, 'year')[calc.take]("year");
      }
      return date;
    },
    setInvalid() {
      this.date = dayjs.bind(this)("invalid");
    },
    setInitialDate(key, value) {
      const current = dayjs.bind(this)();

      this.date = dayjs.bind(this)().set(key, parseInt(value));

      // if the inital day moved the month, let's move it back
      if (key === "date" && current.month() !== this.date.month()) {
        this.date = current.endOf("month");
      }

      return this.date;
    },
  }
};
</script>