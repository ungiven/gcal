<template>
  <div class="calendar-item">
    <h3>{{ name }}</h3>
    {{ start }}-{{ end }}
  </div>
</template>

<script>
export default {
  props: ["name", "start", "end"],
};
</script>

<style scoped>
.calendar-item {
  background-color: #ddd;
  margin-bottom: 10px;
}
</style>
