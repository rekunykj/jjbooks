<template>
  <div id="app">
    <b-nav pills align="left" class="mb-4">
      <b-nav-item to="/" exact-active-class="active">Home</b-nav-item>
      <b-nav-item to="/books" exact-active-class="active">Books</b-nav-item>
      <b-nav-item to="/orders" exact-active-class="active">Orders</b-nav-item>
      <b-nav-item to="/about" exact-active-class="active">About</b-nav-item>
    </b-nav>
    <router-view class="container-xl"/>
  </div>
</template>

<style>

</style>
