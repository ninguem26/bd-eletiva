import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import axios from 'axios'

//import VueGlobalVariable from 'vue-global-var'

Vue.prototype.$http = axios;

Vue.config.productionTip = false

/*Vue.use(VueGlobalVariable, {
  globals: {
    db: "coachdb"
  }
})*/

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app')
