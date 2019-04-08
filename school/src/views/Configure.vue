<template>
    <div class="configure">
        <h1>Configuração</h1>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">DB Ativo: <span class="card-subtitle mb-2 text-muted">{{ $store.state.db }}</span></h5>
                <div class="row">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Banco de Dados</h5>
                            <form>
                                <div class="form-group form-check">
                                    <input v-model="db" name="bd" value="couchdb" type="radio" class="form-check-input" id="db-couchdb">
                                    <label class="form-check-label" for="db-couchdb">CouchDB</label>
                                </div>
                                <div class="form-group form-check">
                                    <input v-model="db" name="bd" value="basex" type="radio" class="form-check-input" id="db-basex">
                                    <label class="form-check-label" for="db-basex">BaseX</label>
                                </div>
                                <button @click="changeDB" type="button" class="btn btn-primary">Salvar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Configure",
        data() {
            return {
                db: ""
            }
        },
        methods: {
            changeDB() {
                this.$store.commit('change', this.db);
            }
        },
        created() {
            this.db = this.$store.state.db;
        }
    }
</script>

<style scoped>

</style>