<template>
  <div>
    <div v-if="editMode" class="calendar-item">
      <div class="calendar-item-head">{{ day(start) }}</div>
      <div class="calendar-item-body edit-form">
        <form :action="'/update/' + itemId" method="POST">
          <input class="event-name" name="name" type="text" :value="name" />
          
          
          <div class="form-item">
            <label for="start">start</label>
            <input name="start" class="time" type="time" :value="time(start).substring(0, 5)" required />
          </div>
          <div class="form-item">
            <label for="end">End</label>
            <input name="end" class="time" type="time" :value="time(end).substring(0, 5)" required />
          </div>

          <div class="form-item">
            <label for="date">Date</label>
            <input name="date" class="time" type="date" required />
          </div>

          <input type="hidden" name="_token" v-bind:value="csrf" />
          <input type="hidden" name="id" :value="itemId" />
        </form>
      </div>
      <div class="calendar-item-foot"><p
            class="edit-button edit-cancel"
            v-on:click="editMode = !editMode"
            title="Cancel"
          >
            ⮨
          </p>
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
    </div>
    <div class="calendar-item" v-else>
      <div class="calendar-item-head">{{ day(start) }}</div>
      <div class="calendar-item-body">
        <h3>{{ name }}</h3>
        

        <p class="time">
          {{ time(start).substring(0, 5) }}-{{ time(end).substring(0, 5) }}
        </p>
      </div>
      <div class="calendar-item-foot"><p
          class="edit-button"
          v-on:click="editMode = !editMode"
          title="Edit item"
        >
          ✎
        </p></div>
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
  font-family: 'open sans';
  color: rgb(32, 129, 255);
  font-size: 16px;
  text-transform: capitalize;
  font-weight: normal;
  padding: 0;
  margin-top: 0;

}
input {
  width: 100%;
  border-width: 0 0 1px 1px;
  border-style: solid;
  border-color: #bbb;
  color: #555;
}

.calendar-item {
  display: grid;
  grid-template-columns: 8% auto 4%;
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

.time {
  font-family: 'Open Sans', 'Tahoma', 'sans-serif';
  font-size: 11px;
  padding: 0;
  margin: 0;
  color: #666;
}

.calendar-item-body {
  display: grid;
  /*grid-template-columns: 93% auto;*/
  align-items: start;
  grid-template-rows: auto auto;
  margin-bottom: 10px;
  border-width: 0 0 1px 1px;
  border-style: solid;
  border-color: #bbb;
  padding-left: 5px;
  padding-right: 5px;
  /*background-color: rgb(32, 129, 255);*/
  background-color: white;
  color: rgb(32, 129, 255);
}

.edit-form {
  display: block;
}

.edit-form form {
  display: grid;
  grid-template-columns: auto auto auto auto;
  grid-template-rows: auto auto;
  grid-template-areas: "name name name name";
  grid-gap: 2px;
  margin-bottom: 5px;
  }

.event-name {
  grid-area: name;
}

p.edit-button {
  /*border-radius: 10px;*/
  font-size: 10px;
  color: rgb(32, 129, 255);
  border: 1px solid rgb(32, 129, 255);
  margin: 0;
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

.calendar-item-foot {
  display: grid;
  grid-template-columns: auto;
  grid-template-rows: auto auto auto;
  padding: 0 0 10px 0;
}


</style>
