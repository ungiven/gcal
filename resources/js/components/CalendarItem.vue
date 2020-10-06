<template>
  <div class="calendar-item">
    <h3>{{ name }}</h3>
    {{ start }}
  </div>
</template>

<script>
export default {
  props: ["name", "start"],
};
</script>

<style scoped>
.calendar-item {
  background-color: #ddd;
  margin-bottom: 10px;
}
</style>
