<template>
  <div class="app flex-row align-items-center">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-6">
          <div class="clearfix">
            <h1 class="float-left display-3 mr-4">ERR!!!</h1>
            <h4 class="pt-3">Oops! Missing token.</h4>
            <p class="text-muted">The token was not found. Please try again later!</p>
          </div>
          <div class="input-prepend input-group">
            <!-- <span class="input-group-addon"><i class="fa fa-search"></i></span> -->
            <!-- <input id="prependedInput" class="form-control" size="16" type="text" placeholder="What are you looking for?"> -->
            <!-- <span class="input-group-btn">
              <button class="btn btn-info" type="button">Search</button>
            </span> -->
          </div>
          <div class="text-center">
            <button class="btn btn-info" @click="backLogin()" type="button" style="width: 140px;">Back</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'InvalidToken',
    methods:{
      backLogin() {
        this.$router.push('login');
      }
    },
  }
</script>
