<template>
   <div>
       

       <div class="panel panel-default">
           <div class="panel-heading">Edit Role</div>
           <div class="panel-body">
               <form v-on:submit="saveForm()">
                   <div class="row">
                       <div class="col-12 form-group">
                           <label class="control-label">Company name</label>
                           <input type="text" v-model="role.name" class="form-control">
                       </div>
                   </div>
                   <div class="row">
                       <div class="col-12 form-group">
                           <label class="control-label">Authority</label>
                           <input type="text" v-model="role.authority" class="form-control">
                       </div>
                   </div>
                   <div class="row">
                       <div class="col-12 form-group">
                           <button class="btn btn-success">Edit</button>
                           <router-link to="/Roles" class="btn btn-primary">Back</router-link>
                       </div>
                   </div>
               </form>
           </div>
       </div>
   </div>
</template>
<script>
   export default {
       mounted() {
           let app = this;
           let id = app.$route.params.id;
           app.roleId = id;
           axios.get('/role/roles/' + id)
               .then(function (resp) {
                   app.role = resp.data;
               })
               .catch(function () {
                   alert("Could not load your role")
               });
       },
       data: function () {
           return {
               roleId: null,
               role: {
                   name: '',
                  authority:'',
               }
           }
       },
       methods: {
           saveForm() {
               event.preventDefault();
               var app = this;
               var newRole = app.role;
               axios.patch('/role/roles/' + app.roleId, newRole)
                   .then(function (resp) {
                       app.$router.replace('/Roles');
                   })
                   .catch(function (resp) {
                       console.log(resp);
                       alert("Could not create your company");
                   });
           }
       }
   }
</script>