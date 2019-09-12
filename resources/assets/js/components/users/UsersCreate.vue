<template>
    <div>
        <div class="form-group">
            <router-link to="/" class="btn btn-default">Back</router-link>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">Create new user</div>
            <div class="panel-body">
                <form v-on:submit.prevent="saveForm()">

                    <div class="row">
                        <div class="col-xs-12 form-group">
                            <label class="control-label">name</label>
                            <input type="text" v-model="user.name" class="form-control">
                            <span class="help-block">{{errors.name}}</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 form-group">
                            <label class="control-label">User email</label>
                            <input type="text" v-model="user.email" class="form-control">
                            <span class="help-block">{{errors.email}}</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 form-group">
                            <label class="control-label">Password</label>
                            <input type="password" v-model="user.password" class="form-control">
                            <span class="help-block">{{errors.password}}</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 form-group">
                            <button class="btn btn-success">Create</button>
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
                user: {
                    name: '',
                    email: '',
                    password: '',
                },
                errors: []
            }
        },
        methods: {
            saveForm() {
                var app = this;
                var newUser = app.user;
                axios.post('/api/v1/users', newUser)
                    .then(function (resp) {

                        if(resp.data.type=='success'){
                            app.$router.push({path: '/'});
                        }else{
                            app.errors=resp.data.errors;
                        }

                    })
                    .catch(function (resp) {
                        console.log(resp);
                        alert("Could not create your user");
                    });
            }
        }
    }
</script>
