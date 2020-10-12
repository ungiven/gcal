<template>
  <div class="calendar-item">
    <h3>{{ name }}<button>E</button></h3>
    {{ start }} ()
  </div>
</template>

<script>
export default {
  props: ["name", "start"],
};
</script>

<style scoped>
h3 {
  margin: 0;
  padding: 0;
}
.calendar-item {
  background-color: rgb(163, 145, 243);
  margin-bottom: 10px;
  border: 1px solid rgb(206, 111, 250);
  border-radius: 10px;
  padding-left: 5px;
}
</style>
