import createPersistedState from 'vuex-persistedstate'

export default ({ store }) => {
  createPersistedState({
    storage: sessionStorage,
    paths: ['user', 'dark']
  })(store)
}
