<template>
  <div class="axb">
    <h2>Events</h2>
    <h2 v-if="items.length < 1" class="no-events">No events this week.</h2>
    <calendar-item
      v-for="item in items"
      :key="item.id"
      :name="item.name"
      :start="item.start"
      :end="item.end"
      :itemId="item.id"
      :csrf="csrf"
      :htmlLink="item.htmlLink"
    ></calendar-item>
  </div>
</template>

<script>
export default {
  props: ["title", "items", "csrf"],
};
</script>

<style scoped>
h2 {
  margin-top: 0;
}

.no-events {
  background-color: white;
  border: none;
  color: #555;
}
</style>
