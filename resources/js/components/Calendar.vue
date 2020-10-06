<template>
    <div class="axb">
        <h1>Calendar</h1>
        {{ title }}
        {{ saker.name }}
        {{ saker.start }}
        {{ saker.end }}
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
    props: ["title", "saker"]
};
</script>
