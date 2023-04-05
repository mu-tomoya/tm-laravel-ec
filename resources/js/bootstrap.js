import _ from "lodash";
window._ = _;

import axios from "axios";
window.axios = axios;

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

import MicroModal from "micromodal";

MicroModal.init({
    disableScroll: true,
});
