<template>
  <div>
    <div v-if="editMode" class="calendar-item edit-form">
      <form :action="'update/' + itemId" method="POST">
        <input type="hidden" name="_token" v-bind:value="csrf" />
        <button v-on:click="editMode = !editMode">✗</button>
        <input name="name" type="text" :value="name" /><br />
        <input name="date" type="date" />
        <input name="start" type="time" />
        <input type="submit" value="Update" name="submit" />
      </form>
    </div>
    <div class="calendar-item" v-else>
      <button v-on:click="editMode = !editMode">✎</button>
      <h3>{{ name }}</h3>
      {{ start }}
    </div>
  </div>
</template>

<script>
export default {
  props: ["name", "start", "end", "itemId", "csrf"],
  data: () => {
    return { editMode: false };
  },
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

.calendar-item button {
  float: right;
  border-radius: 10px;
  color: green;
  border: 1px solid black;
}

.calendar-item button:hover {
  background-color: white;
}

.edit-form button {
  color: red;
  border: 1px solid red;
}
</style>
