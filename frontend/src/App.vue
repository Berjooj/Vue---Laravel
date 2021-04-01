<template>
  <div id="app">
    <nav>
      <div class="nav-wrapper blue darken-1">
        <a href="#" class="brand-logo center">Adicionar Usuário</a>
        <button
          class="waves-effect waves-light btn-small"
          v-on:click="randomUsers()"
        >
          Gerar 10 usuários aleatórios
        </button>
        <button
          class="waves-effect waves-light btn-small"
          v-on:click="randomCars()"
        >
          Gerar 10 carros aleatórios
        </button>
      </div>
    </nav>

    <div class="container">
      <h1>Usuários</h1>
      <form @submit.prevent="store">
        <label>Nome</label>
        <input type="text" placeholder="Nome" v-model="user.name" />
        <label>CPF</label>
        <input type="number" placeholder="CPF" v-model="user.cpf" />

        <button class="waves-effect waves-light btn-small">
          Salvar<i class="material-icons left">save</i>
        </button>
      </form>

      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>NOME</th>
            <th>CPF</th>
            <th>OPÇÕES</th>
          </tr>
        </thead>

        <tbody>
          <tr v-for="user of users" :key="user.id">
            <td>{{ user.id }}</td>
            <td>{{ user.name }}</td>
            <td>{{ user.cpf }}</td>
            <td>
              <button
                @click="update(user)"
                class="waves-effect btn-small blue darken-1"
              >
                <i class="material-icons">create</i>
              </button>
              <button
                @click="remove(user)"
                class="waves-effect btn-small red darken-1"
              >
                <i class="material-icons">delete_sweep</i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="container">
      <h1>Carros</h1>
      <form @submit.prevent="storeCar">
        <label>Marca</label>
        <input type="text" placeholder="Marca" v-model="car.brand" />
        <label>Placa</label>
        <input type="text" placeholder="Placa" v-model="car.plate" />
        <label>Ano</label>
        <input type="number" placeholder="Ano" v-model="car.year" />

        <button class="waves-effect waves-light btn-small">
          Salvar<i class="material-icons left">save</i>
        </button>
      </form>

      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>MARCA</th>
            <th>PLACA</th>
            <th>ANO</th>
            <th>OPÇÕES</th>
          </tr>
        </thead>

        <tbody>
          <tr v-for="car of cars" :key="car.id">
            <td>{{ car.id }}</td>
            <td>{{ car.brand }}</td>
            <td>{{ car.plate }}</td>
            <td>{{ car.year }}</td>
            <td>
              <button
                @click="updateCar(car)"
                class="waves-effect btn-small blue darken-1"
              >
                <i class="material-icons">create</i>
              </button>
              <button
                @click="removeCar(car)"
                class="waves-effect btn-small red darken-1"
              >
                <i class="material-icons">delete_sweep</i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="container">
      <h1>Reservas</h1>
      <form @submit.prevent="storeCar">
        <label>ID do Carro</label>
        <input type="number" placeholder="ID do Carro" v-model="car.id" />
        <label>ID do Usuário</label>
        <input type="number" placeholder="ID usuário" v-model="car.user_id" />
        <span>Para editar você precisa deletar a reserva primeiro.</span><br />
        <button class="waves-effect waves-light btn-small">
          Salvar<i class="material-icons left">save</i>
        </button>
      </form>

      <table>
        <thead>
          <tr>
            <th>ID carro</th>
            <th>ID usuário</th>
            <th>Data</th>
            <th>OPÇÕES</th>
          </tr>
        </thead>

        <tbody>
          <tr v-for="car of cars" :key="car.id">
            <td>{{ car.id }}</td>
            <td>{{ car.user_id }}</td>
            <td>{{ car.updated_at }}</td>
            <td>
              <button
                @click="storeReservation(car)"
                class="waves-effect btn-small blue darken-1"
              >
                <i class="material-icons">create</i>
              </button>
              <button
                @click="removeReservation(car)"
                class="waves-effect btn-small red darken-1"
              >
                <i class="material-icons">delete_sweep</i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import Users from "./services/users";
import Cars from "./services/cars";

export default {
  data() {
    return {
      user: {
        id: "",
        update_at: true,
        name: "",
        cpf: "",
      },
      users: [],
      car: {
        id: "",
        user_id: "",
        update_at: true,
        brand: "",
        year: "",
        plate: "",
      },
      cars: [],
    };
  },

  mounted() {
    this.index();
  },

  methods: {
    //Atualiza a tabela buscando os dados no banco
    index() {
      Users.index().then((response) => {
        this.users = response.data;
      });

      Cars.index().then((response) => {
        this.cars = response.data;
      });
    },

    //Salva ou edita um user baseado no id sendo null ou não
    store() {
      let user;
      if (!this.user.id) {
        user = {
          user: {
            name: this.user.name,
            cpf: this.user.cpf,
          },
        };

        Users.store(user).then((response) => {
          alert(response.statusText);
          this.index();
          this.user.name = "";
          this.user.cpf = "";
        });
      } else {
        user = {
          user: {
            id: this.user.id,
            updated_at: true,
            name: this.user.name,
            cpf: this.user.cpf,
          },
        };

        Users.update(user).then((response) => {
          alert(response.statusText);
          this.index();
          this.user.name = "";
          this.user.cpf = "";
        });
      }
    },

    //Salva ou edita um carro baseado no id sendo null ou não
    storeCar() {
      let car;
      if (!this.car.id) {
        car = {
          car: {
            brand: this.car.brand,
            plate: this.car.plate,
            year: this.car.year,
            user_id: this.car.user_id,
          },
        };

        Cars.store(car).then(() => {
          this.index();
          this.car.brand = "";
          this.car.plate = "";
          this.car.year = "";
          this.car.user_id = "";
        });
      } else {
        car = {
          car: {
            id: this.car.id,
            updated_at: true,
            brand: this.car.brand,
            plate: this.car.plate,
            year: this.car.year,
            user_id: this.car.user_id,
          },
          reservation: {
            user_id: this.car.user_id,
            car_id: this.car.id,
          },
        };

        Cars.update(car).then((response) => {
          alert(response.statusText);
          Cars.storeReservation(car).then(() => {
            // console.log(res);
            this.index();
            this.car.brand = "";
            this.car.plate = "";
            this.car.year = "";
            this.car.user_id = "";
          });
        });
      }
    },

    //Para atualizar o usuário e/ou carro, basta chamar o evento e clicar no botão para salvar
    //como se fosse para criar um elemento novo no banco, porém, desta vez, existe um id do elemento
    //logo o programa irá atualizar o elemento já existente.
    //Sem id = objeto novo
    //Com id = apenas edita um existente

    //Atualiza a reserva
    storeReservation(car) {
      if (this.car.user_id == -1) this.car.user_id = null;

      this.car = car;
    },

    //Atualiza o user
    update(user) {
      this.user = user;
    },

    //Atualiza um carro
    updateCar(car) {
      this.car = car;
    },

    //Remove um usuário
    remove(user) {
      this.user = user;

      user = {
        user: {
          id: this.user.id,
          name: this.user.name,
          cpf: this.user.cpf,
        },
      };

      Users.delete(user).then((response) => {
        alert(response.statusText);
        this.index();
        this.user.name = "";
        this.user.cpf = "";
      });
    },

    //Remove um carro
    removeCar(car) {
      this.car = car;

      car = {
        car: {
          id: this.car.id,
          updated_at: true,
          brand: this.car.brand,
          plate: this.car.plate,
          year: this.car.year,
        },
      };

      Cars.delete(car).then((response) => {
        alert(response.statusText);
        this.index();
        this.car.brand = "";
        this.car.plate = "";
        this.car.year = "";
      });
    },

    //Retira uma reserva da tabela alterando o id do usuário para null
    removeReservation(car) {
      this.car = car;

      let re = {
        reservation: {
          user_id: this.car.user_id,
          car_id: this.car.id,
        },
      };

      Cars.deleteReservation(re).then((response) => {
        alert(response.data);
        this.index();
      });
    },

    //Gera Usuários aleatórios
    randomUsers() {
      Users.rng().then(() => {
        this.index();
      });
    },

    //Gera Carros aleatórios
    randomCars() {
      Cars.rng().then(() => {
        this.index();
      });
    },
  },
};
</script>

<style>
</style>
