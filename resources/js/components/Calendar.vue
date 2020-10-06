<template>
  <div class="axb">
    <h1>Calendar</h1>
    {{ title }}

        <calendar-item v-for="sak in saker" :key="sak.id" :name="sak.name" :start="sak.start" :end="sak.end"></calendar-item>
    </div>
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
  props: ["title", "saker"],
};
</script>
