import Calendar from "./components/Calendar.vue";
import DateInput from "./components/Input/DateInput.vue";
import DateTimeInput from "./components/Input/DateTimeInput.vue";
import DateField from "./components/Field/DateField.vue";
import './fonts.scss';

panel.plugin("vbadvanced/kirby-persian", {
  created(app) {
    document.getElementsByTagName('html')[0].setAttribute('data-font', 'Vazir');
  },
  components: {
    'k-calendar': Calendar,
    'k-date-input': DateInput,
    'k-datetime-input': DateTimeInput,
  },
  fields: {
    date: DateField,
  },
});