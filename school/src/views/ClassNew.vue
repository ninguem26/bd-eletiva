<template>
    <div class="class-new">
        <h1>Novo Estudante</h1>
        <div class="card">
            <div class="card-body">
                <form>
                    <div class="form-group">
                        <label for="inputDomainName">Nome da Disciplina</label>
                        <input v-model="domainName" type="text" class="form-control" id="inputDomainName" placeholder="Nome da disciplina">
                    </div>
                    <div class="form-group">
                        <label for="inputGrade">Período</label>
                        <input v-model="grade" type="text" class="form-control" id="inputGrade" placeholder="Período da turma">
                    </div>
                    <div class="form-group">
                        <label for="inputYear">Ano</label>
                        <input v-model="year" type="text" class="form-control" id="inputYear" placeholder="Ano da turma">
                    </div>
                    <button @click="addClass" type="button" class="btn btn-primary">Enviar</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "ClassNew",
        data() {
            return {
                domainName: "",
                grade: "",
                year: ""
            }
        },
        methods: {
            addClass() {
                if(this.$store.state.db == "couchdb") {
                    this.$http.get('http://127.0.0.1:5984/_uuids').then(({data}) => {
                        this.$http.put('http://127.0.0.1:5984/school/' + data['uuids'][0], {
                            doc_type: "classes",
                            domainName: this.domainName,
                            grade: this.grade,
                            year: this.year
                        }).then(({data}) => {
                            this.$router.push({name: "classroom"});
                        });
                    });
                } else if(this.$store.state.db == "basex") {
                    this.$http.post('http://localhost:8000/api/classes', {
                        data: {
                            domainName: this.domainName,
                            grade: this.grade,
                            year: this.year
                        }
                    }).then(function (response) {
                        console.log(response);
                    });
                }
            }
        }
    }
</script>

<style scoped>

</style>