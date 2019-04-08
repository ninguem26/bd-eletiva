<template>
    <div class="student-home">
        <h1>Estudantes</h1>
        <div class="card">
            <div class="card-header">
                <form class="form-inline">
                    <router-link to="/student/new"><button type="button" class="btn btn-success mb-2"><i class="fa fa-plus"></i> Novo</button></router-link>
                    <div class="form-group mx-sm-3 mb-2">
                        <label for="search" class="sr-only">Busca</label>
                        <input v-model="city" type="text" class="form-control" id="search" placeholder="Filtre estudantes por cidade...">
                    </div>
                    <button @click="searchByCity" type="button" class="btn btn-primary mb-2">Buscar</button>
                </form>
            </div>
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">Matrícula</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Código da Turma</th>
                    <th scope="col">Opção</th>
                </tr>
                </thead>
                <tbody v-for="student in students">
                    <tr>
                        <th scope="row">{{ student.id }}</th>
                        <td>{{ student.name }}</td>
                        <td>{{ student.classId }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <button class="btn btn-info" type="button" data-toggle="collapse" :data-target="'#collapse-'+student.id" aria-expanded="false" :aria-controls="'collapse-'+student.id">
                                    <i class="fa fa-plus"></i>
                                </button>
                                <button class="btn btn-danger" type="button" @click="deleteStudent(student.id)"><i class="fa fa-trash-alt"></i></button>
                            </div>
                        </td>
                    </tr>
                    <tr class="collapse" :id="'collapse-'+student.id">
                        <td colspan="4">
                            <div class="card card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <h5 class="card-title">Data de Nascimento</h5>
                                                <p class="card-text">{{ student.birth }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <h5 class="card-title">Cidade</h5>
                                                <p class="card-text">{{ student.city }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <h5 class="card-title">Endereço</h5>
                                                <p class="card-text">{{ student.address}}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <h5 class="card-title">Telefone</h5>
                                                <p class="card-text">{{ student.phone }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    function Student(student) {
        this.id = student.id;
        this.name = student.name;
        this.birth = student.birth;
        this.city = student.city;
        this.address = student.address;
        this.phone = student.phone;
        this.classId = student.classId;
    }

    export default {
        name: "student-home",
        data() {
            return {
                students: [],
                city: ""
            }
        },
        methods: {
            getData() {
                this.$http.get('http://127.0.0.1:5984/school/_design/students/_view/by_city').then(({ data }) => {
                    data['rows'].forEach(student => {
                        this.students.push(new Student(student.value));
                    });
                });
            },
            deleteStudent(id) {
                this.$http.get('http://admin:123456@127.0.0.1:5984/school/'+id).then(({ data }) => {
                    this.$http.delete('http://admin:123456@127.0.0.1:5984/school/'+id+'?rev='+data['_rev']).then(({ data }) => {
                        if(data['ok']) {
                            for(var i = 0; i < this.students.length; i++) {
                                if(this.students[i].id == id) {
                                    this.students.splice(i, 1);
                                }
                            }
                        }
                    });
                });
            },
            searchByCity() {
                this.students = [];

                if(this.city == "") {
                    this.getData();
                } else {
                    this.$http.get('http://127.0.0.1:5984/school/_design/students/_view/by_city?key="' + this.city + '"').then(({data}) => {
                        data['rows'].forEach(student => {
                            this.students.push(new Student(student.value));
                        });
                    });
                }
            }
        },
        created() {
            this.getData();
        }
    }
</script>