<template>
  <div class="axb">
    <h1>Calendar</h1>
    <h2>{{ title }}</h2>

    <calendar-item
      v-for="item in items"
      :key="item.id"
      :name="item.name"
      :start="item.start"
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
  props: ["title", "items"],
};
</script>
