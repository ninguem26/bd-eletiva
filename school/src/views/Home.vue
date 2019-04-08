<template>
  <div class="home">
    <h1>Bem vindo!</h1>
    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col-md-3">
            <div class="card">
              <div class="card-body">
                <router-link to="student">Estudantes</router-link>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card">
              <div class="card-body">
                <router-link to="classroom">Turmas</router-link>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card">
              <div class="card-body">
                <router-link :to="{ name: 'configure' }">Configurações</router-link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
// @ is an alias to /src
import HelloWorld from '@/components/HelloWorld.vue'

export default {
  name: 'home'
}
</script>
