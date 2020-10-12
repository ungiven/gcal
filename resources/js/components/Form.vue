<template>
  <div id="form">
    <h2>Form goes here</h2>
    <form action="/add" method="post">
      <input type="date" name="date" id="a" />
      <input type="time" name="time" id="b" />
      <input type="time" name="end" id="" />
      <input type="text" name="name" placeholder="Event name" />
      <input type="submit" id="submiButton" value="Add event" />
    </form>
  </div>
</template>

<script>
export default {};
</script>

<style scoped>
textarea,
[type="submit"],
[type="text"] {
  width: 100%;
}

input {
  width: 45%;
}
</style>
