<template>
    <div class="student-new">
        <h1>Novo Estudante</h1>
        <div class="card">
            <div class="card-body">
                <form>
                    <div class="form-group">
                        <label for="inputName">Nome</label>
                        <input v-model="name" type="text" class="form-control" id="inputName" placeholder="Nome do estudante">
                    </div>
                    <div class="form-group">
                        <label for="inputBirth">Data de Nascimento</label>
                        <input v-model="birth" type="text" class="form-control" id="inputBirth" placeholder="Data de nascimento">
                        <small class="form-text text-muted">Data no formato dd/MM/aaaa</small>
                    </div>
                    <div class="form-group">
                        <label for="inputCity">Cidade</label>
                        <input v-model="city" type="text" class="form-control" id="inputCity" placeholder="Cidade do estudante">
                    </div>
                    <div class="form-group">
                        <label for="inputAddress">Endereço</label>
                        <input v-model="address" type="text" class="form-control" id="inputAddress" placeholder="Endereço do estudante">
                    </div>
                    <div class="form-group">
                        <label for="inputPhone">Telefone</label>
                        <input v-model="phone" type="text" class="form-control" id="inputPhone" placeholder="Telefone do estudante">
                    </div>
                    <label for="inputPhone">Turma</label>
                    <div v-for="classroom in classes" class="form-group form-check">
                        <input v-model="classId" name="classroom" :value="classroom.id" type="radio" class="form-check-input" :id="'classroom-'+classroom.id">
                        <label class="form-check-label" :for="'classroom-'+classroom.id">{{ classroom.domainName }}</label>
                    </div>
                    <button @click="addStudent" type="button" class="btn btn-primary">Enviar</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    function Classroom(classroom) {
        this.id = classroom.id;
        this.domainName = classroom.domainName;
    }

    export default {
        name: "StudentNew",
        data() {
            return {
                name: "",
                birth: "",
                city: "",
                address: "",
                phone: "",
                classId: 0,
                classes: []
            }
        },
        methods: {
            getClasses() {
                if(this.$store.state.db == "couchdb") {
                    this.$http.get('http://127.0.0.1:5984/school/_design/classes/_view/by_year').then(({data}) => {
                        data['rows'].forEach(classroom => {
                            this.classes.push(new Classroom(classroom.value));
                        });
                    });
                } else if(this.$store.state.db == "basex") {
                    this.$http.get('http://localhost:8000/api/classes').then(({data}) => {
                        data['data'].forEach(classroom => {
                            this.classes.push(new Classroom(classroom));
                        });
                    });
                }
            },
            addStudent() {
                if(this.$store.state.db == "couchdb") {
                    this.$http.get('http://127.0.0.1:5984/_uuids').then(({data}) => {
                        this.$http.put('http://127.0.0.1:5984/school/' + data['uuids'][0], {
                            doc_type: "students",
                            name: this.name,
                            birth: this.birth,
                            city: this.city,
                            address: this.address,
                            phone: this.phone,
                            classId: this.classId,
                        }).then(({data}) => {
                            this.$router.push({name: "student"});
                        });
                    });
                } else if(this.$store.state.db == "basex") {
                    this.$http.post('http://localhost:8000/api/students', {
                        data: {
                            name: this.name,
                            birth: this.birth,
                            city: this.city,
                            address: this.address,
                            phone: this.phone,
                            classId: this.classId
                        }
                    }).then(function (response) {
                        console.log(response);
                    });
                }
            }
        },
        created() {
            this.getClasses();
        }
    }
</script>

<style scoped>

</style>