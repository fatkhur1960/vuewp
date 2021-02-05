<template>
  <div>
    <i class="fa fa-calendar"></i>
    {{ dateInput | parseDate }}&nbsp;
  </div>
</template>
<script>
import moment from 'moment'

export default {
  name: 'DateFormatted',
  props: {
    dateInput: {
      type: String,
      required: true,
    },
  },
  filters: {
    parseDate: (input) => {
      return moment.utc(new Date(input)).format('D MMM, YYYY')
    },
  },
}
</script>
