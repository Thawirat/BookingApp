import "./bootstrap";
import "bootstrap";
import "bootstrap/dist/css/bootstrap.min.css";
import "../css/app.css";

import Alpine from "alpinejs";
import collapse from "@alpinejs/collapse";

Alpine.plugin(collapse);
Alpine.start();

window.Echo.channel('bookings')
    .listen('.new-booking', (event) => {
        console.log('📢 มีการจองใหม่:', event.booking);
    });

