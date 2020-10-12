<template>
  <div>
    <div v-if="lul"><button v-on:click="lul = !lul">Click</button>lul</div>
    <div v-else><button v-on:click="lul = !lul">Click</button>inte lul</div>
  </div>
</template>


 
<script>
export default {
  /*data: function () {
    return {
      lul: true,
    };
  },*/
  data: () => {
    return {
      lul: true,
    };
  },
};
</script>