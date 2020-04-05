<template>
  <v-app>
    <v-content>
      <v-app-bar color="blue" v-if="verification() === true">
        <div class="mx-auto">
        <v-btn icon>
          <router-link to="/search">
            <v-icon>search</v-icon>
          </router-link>
        </v-btn>
        <v-btn icon>
          <router-link to="/opinion">
            <v-icon>mdi-clipboard</v-icon>
          </router-link>
        </v-btn>
        </div>
      </v-app-bar>
      <router-view></router-view>
    </v-content>
  </v-app>
</template>

<script>
export default {
  name: "App",
  data: () => ({
    //
  }),
  methods: {
    verification() {
      if (localStorage.iduser) {
        return true;
      } else {
        return false;
      }
    }
  }
};
</script>

<style scoped>
nav {
  text-align: center;
}
</style>