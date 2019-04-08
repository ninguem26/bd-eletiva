<template>
    <div class="student-class">
        <h1>Estudantes</h1>
        <div v-if="students.length > 0" class="card">
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
        <div v-else class="card">
            <div class="card-body">
                <h2>Não há alunos nessa turma, <router-link :to="{ name: 'classroom' }"><a href="#">voltar à lista de turmas</a></router-link></h2>
            </div>
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
        name: "StudentClass",
        props: ["id"],
        data() {
            return {
                students: []
            }
        },
        methods: {
            getData() {
                if(this.$store.state.db == "couchdb") {
                    this.$http.get('http://127.0.0.1:5984/school/_design/classes/_view/get_students?key="' + this.id + '"').then(({data}) => {
                        data['rows'].forEach(student => {
                            this.students.push(new Student(student.value));
                        });
                    });
                } else if(this.$store.state.db == "basex") {
                    this.$http.get('http://localhost:8000/api/classes/'+ this.id +'/students').then(({data}) => {
                        data['data'].forEach(student => {
                            this.students.push(new Student(student));
                        });
                    });
                }
            },
        },
        created() {
            this.getData();
        }
    }
</script>