<template>
    <div class="class-home">
        <h1>Turmas</h1>
        <div class="card">
            <div class="card-header">
                <form class="form-inline">
                    <router-link to="/classroom/new"><button type="button" class="btn btn-success mb-2"><i class="fa fa-plus"></i> Novo</button></router-link>
                    <div class="form-group mx-sm-3 mb-2">
                        <label for="search" class="sr-only">Busca</label>
                        <input v-model="year" type="text" class="form-control" id="search" placeholder="Filtre turmas por ano...">
                    </div>
                    <button @click="searchByYear" type="button" class="btn btn-primary mb-2">Buscar</button>
                </form>
            </div>
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">Código</th>
                    <th scope="col">Disciplina</th>
                    <th scope="col">Período</th>
                    <th scope="col">Ano</th>
                    <th scope="col">Opção</th>
                </tr>
                </thead>
                <tbody v-for="classroom in classes">
                    <tr>
                        <th scope="row">{{ classroom.id }}</th>
                        <td>{{ classroom.domainName }}</td>
                        <td>{{ classroom.grade }}</td>
                        <td>{{ classroom.year }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <button class="btn btn-info" type="button" @click="more(classroom.id)"><i class="fa fa-plus"></i></button>
                                <button class="btn btn-danger" type="button" @click="deleteClass(classroom.id)"><i class="fa fa-trash-alt"></i></button>
                            </div>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    function Classroom(classroom) {
        this.id = classroom.id;
        this.domainName = classroom.domainName;
        this.grade = classroom.grade;
        this.year = classroom.year;
    }

    export default {
        name: "class-home",
        data() {
            return {
                year: "",
                classes: []
            }
        },
        methods: {
            getData() {
                if(this.$store.state.db == "couchdb") {
                    this.$http.get('http://127.0.0.1:5984/school/_design/classes/_view/by_year').then(({ data }) => {
                        data['rows'].forEach(classroom => {
                            this.classes.push(new Classroom(classroom.value));
                        });
                    });
                } else if(this.$store.state.db == "basex") {
                    this.$http.get('http://@localhost:8984/rest/AB2/classes.xml?method=json&json=format=jsonml', {
                        auth: {
                            username: 'admin',
                            password: 'admin'
                        }
                    }).then(({ data }) => {
                        console.log(data);
                    });
                }
            },
            searchByYear() {
                this.classes = [];

                if(this.year == "") {
                    this.getData();
                } else {
                    this.$http.get('http://127.0.0.1:5984/school/_design/classes/_view/by_year?key="' + this.year + '"').then(({data}) => {
                        data['rows'].forEach(classroom => {
                            this.classes.push(new Classroom(classroom.value));
                        });
                    });
                }
            },
            deleteClass(id) {
                this.$http.get('http://admin:123456@127.0.0.1:5984/school/'+id).then(({ data }) => {
                    this.$http.delete('http://admin:123456@127.0.0.1:5984/school/'+id+'?rev='+data['_rev']).then(({ data }) => {
                        if(data['ok']) {
                            for(var i = 0; i < this.classes.length; i++) {
                                if(this.classes[i].id == id) {
                                    this.classes.splice(i, 1);
                                }
                            }
                        }
                    });
                });
            },
            more(id) {
                this.$router.push({ name: "studentClass", params: { id: id } })
            }
        },
        created() {
            this.getData();
            console.log(this.$store.state.db);
        }
    }
</script>