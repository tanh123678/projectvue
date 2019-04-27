<template>
    <div>
        

        <div class="panel panel-default">
            <div class="panel-heading">Create new Role</div>
            <div class="panel-body">
                <form v-on:submit="saveForm()">
                    <div class="row">
                        <div class="col-12 form-group">
                            <label class="control-label">Role name</label>
                            <input type="text" v-model="company.name" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 form-group">
                            <label class="control-label">Authority</label>
                            <input type="text" v-model="company.authority" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 form-group">
                            <button class="btn btn-success">Create</button>
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
        data: function () {
            return {
                company: {
                    name: '',
                    authority: '',
                }
            }
        },
        methods: {
            saveForm() {
                event.preventDefault();
                var app = this;
                var newCompany = app.company;
                axios.post('role/roles', newCompany)
                    .then(function (resp) {
                        app.$router.push({path: '/Roles'});
                    })
                    .catch(function (resp) {
                        console.log(resp);
                        alert("Could not create your role");
                    });
            }
        }
    }
</script>