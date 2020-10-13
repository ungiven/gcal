<template>
  <div class="axb">
    <h2>Events</h2>

    <calendar-item
      v-for="item in items"
      :key="item.id"
      :name="item.name"
      :start="item.start"
      :end="item.end"
      :itemId="item.id"
      :csrf="csrf"
    ></calendar-item>
  </div>
</template>

<script>
/*var calendar = new Vue({
    el: "#calendar",
    data: {
        message: "CAL"
    }
});*/

export default {
  props: ["title", "items", "csrf"],
};
</script>

<style scoped>

</style>
