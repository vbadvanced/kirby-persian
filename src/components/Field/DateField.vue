<template>
  <k-field :input="_uid" v-bind="$props" v-bind:class="{'k-date-field': true, 'k-jalali-date-field': isJalali}">
    <k-input
      ref="input"
      :id="_uid"
      :type="inputType"
      :value="date"
      :jalali="isJalali"
      v-bind="$props"
      theme="field"
      v-on="listeners"
    >
      <template slot="icon">
        <k-dropdown>
          <k-button
            :icon="icon"
            :tooltip="$t('date.select')"
            class="k-input-icon-button"
            tabindex="-1"
            @click="$refs.dropdown.toggle()"
          />
          <k-dropdown-content ref="dropdown" align="right">
            <Calendar
              :value="date"
              :jalali="isJalali"
              @input="onInput($event); $refs.dropdown.close()"
            />
          </k-dropdown-content>
        </k-dropdown>
      </template>
    </k-input>
  </k-field>
</template>

<script>
import Calendar from "../Calendar";
import { toBoolean } from '../../helpers';

export default {
  extends: "k-date-field",
  components: { Calendar },
  props: {  
    jalali: String
  },
  computed: {
    inputType() {
      return this.time === false ? "date" : "datetime";
    },
    isJalali() {
      return  (this.jalali === 'auto') 
        ? (this.$store.state.languages.current ? this.$store.state.languages.current.code : false) === 'fa'
        : toBoolean(this.jalali);
    }
  }
}
</script>

<style lang="scss">
.k-date-field {
  &.k-jalali-date-field {
    .k-date-input, .k-datetime-input {
      direction: ltr;
    }
  }
}
</style>