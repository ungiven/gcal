require("./bootstrap");

window.Vue = require("vue");

Vue.component("calendar", require("./components/Calendar.vue").default);

Vue.component(
    "calendar-item",
    require("./components/CalendarItem.vue").default
);

Vue.component("calendar-form", require("./components/Form.vue").default);
Vue.component("test-component", require("./components/Test.vue").default);

const app = new Vue({
    el: "#app"
});
