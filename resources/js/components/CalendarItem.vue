<template>
    <div>
        <div v-if="editMode" class="calendar-item edit-form">
          
            <form :action="'update/' + itemId" method="POST">
                <input class="event-name" name="name" type="text" :value="name" />
                <p class="edit-button" v-on:click="editMode = !editMode" title="Cancel">✖</p>
                <input name="date" type="date" />
                <input name="start" type="time" />
                <input name="end" type="time" />
                <input type="submit" value="✓" name="submit" title="Save changes"/>
                <input type="hidden" name="_token" v-bind:value="csrf" />
            </form>
        </div>
        <div class="calendar-item" v-else>
          <h3>{{ name }}</h3>
            <p class="edit-button" v-on:click="editMode = !editMode" title="Edit item">✎</p>
            
            <p class="time">{{ d(start) }} </p>
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
      d: (ds) => {
        let date = new Date(ds);
        const options = {weekday: 'long'};

        return date.toLocaleTimeString('sv-SE') + date.toLocaleDateString('sv-SE', options);
      }
    }
};
</script>

<style scoped>

input[type="submit"] {
  color: #666;
  padding: 0;
  margin: 0;
  text-align: center;
  width: 16px;
  height: 16px;
  font-size: 11px;
  border: 1px solid #666;
  background-color: white;
}

form {
  
}

h3 {
    margin: 0;
    padding: 0;
}

p.time {
  padding: 0;
  margin: 0;
}
.calendar-item {
    display: grid;
    grid-template-columns: 93% auto;
    align-items: start;
    grid-template-rows: auto auto;
    margin-bottom: 10px;
    border-width: 0 0 1px 1px;
    border-style: solid;
    border-color: #bbb;
    padding-left: 5px;
}

.edit-form {
  display: block;
}

.edit-form form {
  display: grid;
  grid-template-columns: auto auto auto 7%;
  grid-template-rows: auto auto auto;
  grid-template-areas: "name name name .";
  grid-gap: 5px;
}

.event-name {
  grid-area: name;
}

p.edit-button {
    /*border-radius: 10px;*/
    font-size: 10px;
    color: #666;
    border: 1px solid #666;
    margin: 0;
    padding: 0;
    text-align: center;
    width: 14px;
    height: 14px;
}


p.edit-button:hover, input[type="submit"]:hover {
  background-color: #666;
  color: white;
  cursor: pointer;
}

.calendar-item button:hover {
    background-color: white;
}
</style>
