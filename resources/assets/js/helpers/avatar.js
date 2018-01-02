export default {
  setAvatar(key, avatar) {
    window.localStorage.setItem(key, avatar)
  },

  getAvatar(key) {
    return window.localStorage.getItem(key)
  },

  removeAvatar(key) {
    window.localStorage.removeItem(key)
  }
}