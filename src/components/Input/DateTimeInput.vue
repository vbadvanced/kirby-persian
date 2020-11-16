<template>
  <div class="k-datetime-input">
    <DateInput
      ref="dateInput"
      :autofocus="autofocus"
      :required="required"
      :id="id"
      :min="min"
      :max="max"
      :disabled="disabled"
      :value="dateValue"
      :jalali="jalali"
      @input="setDate"
    />
    <k-time-input
      ref="timeInput"
      :required="required"
      :disabled="disabled"
      :value="timeValue"
      v-bind="timeOptions"
      @input="setTime"
    />
  </div>
</template>

<script>
import DateInput from "./DateInput.vue";

export default {
  extends: 'k-datetime-input',
  components: { DateInput },
  props: {
    jalali: Boolean
  }
}

</script>