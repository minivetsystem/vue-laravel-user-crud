<template>
    <div>
        <div class="form-group">
            <router-link :to="{name: 'createUser'}" class="btn btn-success">Create new User</router-link>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">Users list

            <input type="text" v-on:keyup="searchuser" v-model="search" class="form-control">
            </div>
            <div class="panel-body">
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th width="100">&nbsp;</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="user, index in users">
                        <td>{{ user.name }}</td>
                        <td>{{ user.email }}</td>
                        <td>
                            <router-link :to="{name: 'editUser', params: {id: user.id}}" class="btn btn-xs btn-default">
                                Edit
                            </router-link>
                            <a href="#"
                               class="btn btn-xs btn-danger"
                               v-on:click="deleteEntry(user.id, index)">
                                Delete
                            </a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                users: [],
                search:''
            }
        },
        mounted() {
            var app = this;
            var search= app.search;
            axios.get('/api/v1/users?search='+search)
                .then(function (resp) {
                    app.users = resp.data;
                })
                .catch(function (resp) {
                    console.log(resp);
                    alert("Could not load users");
                });
        },
        methods: {
            deleteEntry(id, index) {
                if (confirm("Do you really want to delete it?")) {
                    var app = this;
                    axios.delete('/api/v1/users/' + id)
                        .then(function (resp) {
                            app.users.splice(index, 1);
                        })
                        .catch(function (resp) {
                            alert("Could not delete users");
                        });
                }
            },
            searchuser(){
                var app = this;
                            var search= app.search;
                            axios.get('/api/v1/users?search='+search)
                                .then(function (resp) {
                                    app.users = resp.data;
                                })
                                .catch(function (resp) {
                                    console.log(resp);
                                    alert("Could not load users");
                                });
            }
        }
    }
</script>
