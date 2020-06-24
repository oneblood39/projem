<template>
    <div class="container">
    <!-- /.row -->
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">All Users</h3>

            <div class="card-tools">
              <button class="btn btn-success" data-toggle="modal" data-target="#addNew">Create User <i class="fas fa-user-plus"></i></button>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body table-responsive p-0" style="height: 300px;">
            <table class="table table-head-fixed text-nowrap">

                <tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Type</th>
                  <th>Registered At</th>
                  <th>Modify</th>
                </tr>


                <tr v-for="user in users" :key="user.id">
                  <td>{{user.id}}</td>
                  <td>{{user.name}}</td>
                  <td>{{user.email}}</td>
                  <td>{{user.type}}</td>
                  <td>{{user.created_at | myDate}}</td>
                  <td>
                 <a href="#">Edit
                 <i class="fa fa-edit text-blue"></i>
                 </a>
                 <a href="#">Delete
                 <i class="fa fa-trash text-red"></i>
                 </a>
                  </td>
                </tr>

            </table>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
    </div>

<!-- create user Modal window -->
 <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered">
     <div class="modal-content">
       <div class="modal-header">
         <h5 class="modal-title" id="addNewLabel">Add New User</h5>
         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
           <span aria-hidden="true">&times;</span>
         </button>
       </div>
       <form @submit.prevent="createUser">
       <div class="modal-body">
          <div class="form-group">
          <input v-model="form.name" type="text" name="name" placeholder="Name"
             class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
          <has-error :form="form" field="name"></has-error>
          </div>

          <div class="form-group">
          <input v-model="form.email" type="email" name="email" placeholder="Email Address"
             class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
          <has-error :form="form" field="email"></has-error>
          </div>

          <div class="form-group">
          <textarea v-model="form.bio" name="bio" id="bio" placeholder="Short bio for user(optional)"
             class="form-control" :class="{ 'is-invalid': form.errors.has('bio') }"></textarea>
          <has-error :form="form" field="bio"></has-error>
          </div>

          <div class="form-group">
          <select name="type" v-model="form.type" id="type"
             class="form-control" :class="{ 'is-invalid': form.errors.has('type') }">
             <option value="">Select User Role</option>
             <option value="admin">Admin</option>
             <option value="user">Standart User</option>
             <option value="author">Author</option>
          </select>
          <has-error :form="form" field="type"></has-error>
          </div>

          <div class="form-group">
          <input v-model="form.password" type="password" name="password" id="password"
             class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
          <has-error :form="form" field="password"></has-error>
          </div>

       </div>
       <div class="modal-footer">
         <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
         <button type="submit" class="btn btn-primary">Create</button>
       </div>
       </form>
     </div>
   </div>
 </div>
<!-- create user Modal ends here-->





    <!-- /.row -->
    </div>
</template>

<script>
    export default {
    data(){
     return{
        users : {},
        form: new Form({
            name : '',
            email : '',
            password : '',
            type : '',
            bio : '',
            photo: ''
        })
     }
    },
    methods: {
       loadUsers(){
           axios.get("api/user").then(({ data }) => (this.users = data));
       },
       createUser(){
       this.form.post('api/user');
       $('#addNew').modal('hide')
       }
    },
        created() {
        this.loadUsers();
        }
    }
</script>
