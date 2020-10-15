<template>
  <div>
    <div v-if="editMode" class="calendar-item-body edit-form">
      <div>
        <form :action="'/update/' + itemId" method="POST">
          <input class="event-name" name="name" type="text" :value="name" />
          <p
            class="edit-button"
            v-on:click="editMode = !editMode"
            title="Cancel"
          >
            🡄
          </p>
          <div class="form-item">
            <label for="date">Date</label>
            <input name="date" type="date" required />
          </div>
          <div class="form-item">
            <label for="start">start</label>
            <input name="start" type="time" value="00:00" required />
          </div>
          <div class="form-item">
            <label for="end">End</label>
            <input name="end" type="time" value="00:00" required />
          </div>
          <div class="submit-area">
            <input type="submit" value="✓" name="submit" title="Save changes" />
            <input
              type="submit"
              value="✖"
              name="delete"
              title="Delete Event"
              class="delete-button"
              formnovalidate
            />
          </div>

          <input type="hidden" name="_token" v-bind:value="csrf" />
          <input type="hidden" name="id" :value="itemId" />
        </form>
      </div>
    </div>
    <div class="calendar-item" v-else>
      <div class="calendar-item-head">{{ day(start) }}</div>
      <div class="calendar-item-body">
        <h3>{{ name }}</h3>
        <p
          class="edit-button"
          v-on:click="editMode = !editMode"
          title="Edit item"
        >
          ✎
        </p>

        <p class="time">
          {{ time(start).substring(0, 5) }}-{{ time(end).substring(0, 5) }}
        </p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["name", "start", "end", "itemId", "csrf"],
  data: () => {
    return { editMode: false };
  },
  methods: {
    time: (ds) => {
      let date = new Date(ds);
      const options = { weekday: "short" };

      return date.toLocaleTimeString("sv-SE");
    },
    day: (ds) => {
      let date = new Date(ds);
      const options = { weekday: "short" };
      return date.toLocaleDateString("en-US", options);
    },
  },
};
</script>

<style scoped>
div {
  margin: 0;
  padding: 0;
}

.event-name {
  margin-top: 5px;
}
input {
  width: 100%;
}

.calendar-item {
  display: grid;
  grid-template-columns: 8% auto;
}

.calendar-item-head {
  font-family: "Open sans", "sans-serif";
  font-weight: bold;
  font-size: 14px;
  color: #555;
}

input[type="submit"] {
  color: rgb(32, 129, 255);
  padding: 0;
  margin: 0;
  text-align: center;
  width: 16px;
  height: 16px;
  font-size: 11px;
  border: 1px solid rgb(32, 129, 255);
  background-color: white;
}

h3 {
  margin: 0;
  padding: 0;
}

p.time {
  padding: 0;
  margin: 0;
  color: #666;
}

.calendar-item-body {
  display: grid;
  grid-template-columns: 93% auto;
  align-items: start;
  grid-template-rows: auto auto;
  margin-bottom: 10px;
  border-width: 0 0 1px 1px;
  border-style: solid;
  border-color: #bbb;
  padding-left: 5px;
  /*background-color: rgb(32, 129, 255);*/
  background-color: white;
  color: rgb(32, 129, 255);
}

.edit-form {
  display: block;
}

.edit-form form {
  display: grid;
  grid-template-columns: auto auto auto 6.25%;
  grid-template-rows: auto auto;
  grid-template-areas: "name name name .";
  grid-gap: 5px;
}

.event-name {
  grid-area: name;
}

p.edit-button {
  /*border-radius: 10px;*/
  font-size: 10px;
  color: rgb(32, 129, 255);
  border: 1px solid rgb(32, 129, 255);
  margin: 5px 0 0 0;
  padding: 0;
  text-align: center;
  width: 14px;
  height: 14px;
}

p.edit-button:hover,
input[type="submit"]:hover {
  background-color: rgb(32, 129, 255);
  color: white;
  cursor: pointer;
}

.calendar-item-body button:hover {
  background-color: white;
}

input.delete-button {
  color: rgb(255, 91, 91);
  background-color: white;
  border-color: rgb(255, 91, 91);
}

input.delete-button:hover {
  color: white;
  background-color: rgb(255, 91, 91);
}
</style>
