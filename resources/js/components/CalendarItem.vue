<template>
  <div>
    <div v-if="editMode" class="calendar-item">
      <div class="calendar-item-head">{{ day(start) }}</div>
      <div class="calendar-item-body edit-form">
        <form :action="'/update/' + itemId" method="POST" :id="'event-edit-' + itemId">
          <input class="event-name" name="name" type="text" :value="name" />
          
          <div class="all-day">
            <label class="all-day" for="allday">All day</label>
            <input name="allday" type="checkbox" v-model="checkedBox" v-bind:checked="allDay">
            
          </div>
          
          
          <div class="form-item">
            <label for="start">Start</label>
            <input name="start" class="time" type="time" :value="time(start).substring(0, 5)" v-bind:disabled="checkedBox" required />
          </div>
          <div class="form-item">
            <label for="end">End</label>
            <input name="end" class="time" type="time" :value="time(end).substring(0, 5)" v-bind:disabled="checkedBox" required />
          </div>

          <div class="form-item">
            <label for="date">Date</label>
            <input name="date" class="time" type="date" :value="date(start)" required />
          </div>

          <input type="hidden" name="_token" v-bind:value="csrf" />
          <input type="hidden" name="id" :value="itemId" />
        </form>
      </div>
      <div class="calendar-item-foot"><p
            class="edit-button edit-cancel"
            v-on:click="editMode = !editMode"
            title="Cancel"
            form="event-edit"
          >
            &#128469;
          </p>
           <input type="submit" value="✓" name="submit" :form="'event-edit-' + itemId" title="Save changes" class="update-button" />
            <input
              type="submit"
              value="✖"
              name="delete"
              title="Delete Event"
              class="delete-button"
              :form="'event-edit-' + itemId"
              formnovalidate
            />
          
          </div>
    </div>
    <div class="calendar-item" v-else>
      <div class="calendar-item-head">{{ day(start) }}</div>
      <div class="calendar-item-body" v-bind:class="{ 'all-day': allDay}">
        <h3><a :href="htmlLink">{{ name }}</a></h3>
        
        <p class="time all-day" v-if="allDay">All day</p>
        <p class="time" v-else>
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
  props: ["name", "start", "end", "itemId", "csrf", "htmlLink"],
  
  data() {
    return { editMode: false, allDay: (this.start.length == 10), checkedBox: (this.start.length == 10)};
  },
  
  methods: {
    time: (ds) => {
      let date = new Date(ds);
      return date.toLocaleTimeString("sv-SE");
    },
    
    day: (ds) => {
      let date = new Date(ds);
      const options = { weekday: "short" };
      return date.toLocaleDateString("en-US", options);
    },
    
    date: (ds) => {
      let date = new Date(ds);
      const options = { year: 'numeric', month: 'numeric', day: 'numeric' };
      return date.toLocaleDateString("sv-SE", options);
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
  /*color: rgb(32, 129, 255);*/
  color: rgb(87, 156, 87);
  padding: 0;
  margin: 0;
  text-align: center;
  width: 16px;
  height: 16px;
  font-size: 11px;
  /*border: 1px solid rgb(32, 129, 255);*/
  border: 1px solid rgb(87, 156, 87);
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
  grid-template-columns: auto auto auto fit-content(50%);
  grid-template-rows: auto auto;
  grid-template-areas: "name name name .";
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

input.update-button:hover {
  color: white;
  background-color: rgb(87, 156, 87)
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



.calendar-item-body.all-day {
  display: grid;
  grid-template-columns: 80% auto;
  align-items: end;
  grid-column-gap: 10px;
}

.calendar-item-body.all-day p {
  text-align: right;
  margin-right: 5px;
}


input[type="checkbox"] {
  /*display: inline!important;*/
  width: unset;
  margin: 0;
  padding: 0;
}

label.all-day {
  display: inline!important;
}

.edit-form div.all-day {
  align-self: end;
}




</style>
