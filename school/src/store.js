import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    db: "basex"
  },
  mutations: {
    change (state, value) {
      state.db = value;
    }
  },
  actions: {

  }
})
