<template>
    <div class="container">
    <!-- /.row -->
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">All Users</h3>

            <div class="card-tools">
              <button class="btn btn-success" @click="newModal">Create User <i class="fas fa-user-plus"></i></button>
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
                  <td>{{user.data.id}}</td>
                  <td>{{user.data.name}}</td>
                  <td>{{user.data.email}}</td>
                  <td>{{user.data.type}}</td>
                  <td>{{user.data.created_at | myDate}}</td>
                  <td>
                 <a href="#" @click="editModal(user)">Edit
                 <i class="fa fa-edit text-blue"></i>
                 </a>
                 <a href="#" @click="deleteUser(user.data.id)">Delete
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
         <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Add New User</h5>
         <h5 class="modal-title" v-show="editmode" id="addNewLabel">Update User</h5>
         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
           <span aria-hidden="true">&times;</span>
         </button>
       </div>
       <form @submit.prevent="editmode ? updateUser() : createUser()">
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
         <button v-show="editmode" type="submit" class="btn btn-success">Update</button>
         <button v-show="!editmode" type="submit" class="btn btn-primary">Create</button>
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
        editmode : false,
        users : {},
        form: new Form({
            id : '',
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
       updateUser(){
         this.form.put('api/user/'+this.form.id)
         .then(() => {
          //success
          Fire.$emit('AfterCreate');
          $('#addNew').modal('hide')
              Swal.fire(
                  'Updated!',
                   'success'
             )
         })
         .catch(() => {
             Swal.fire(
             'Cant Updated!',
              'error'
             )
         });
       },
       editModal(user){
          this.editmode = true;
          this.form.reset();
          $('#addNew').modal('show');
          this.form.fill(user.data);
       },
       newModal(){
          this.form.reset();
          this.editmode = false;
          $('#addNew').modal('show');

       },
       deleteUser(id){
       Swal.fire({
           title: 'Are you sure?',
           text: "You won't be able to revert this!",
           icon: 'warning',
           showCancelButton: true,
           confirmButtonColor: '#3085d6',
           cancelButtonColor: '#dd3333',
           confirmButtonText: 'Yes, delete it!'
           }).then((result) => {

              //send request to server
              if (result.value) {

              this.form.delete('api/user/'+id).then(()=>{
                    Swal.fire(
                         'Deleted!',
                          'success'
                    )
                    Fire.$emit('AfterCreate');
                   })
                   .catch (()=>{
                    Swal.fire(
                     'Cant Deleted!',
                       'error'
                     )
                   });

              }


           })
       },
       loadUsers(){
           axios.get("api/user").then(({ data }) => (this.users = data.data));
       },
       createUser(){
       this.form.post('api/user')
       .then(()=>{
          Fire.$emit('AfterCreate');
          $('#addNew').modal('hide')
          toast.fire({
            icon: 'success',
            title: 'Kullanıcı ekleme başarılı'
          })
       })
       .catch(()=>{

       })

       }
    },
        created() {
           this.loadUsers();
           Fire.$on('AfterCreate',() => {
               this.loadUsers();
           })
          // setInterval(() => this.loadUsers(), 3000);

        }
    }
</script>
