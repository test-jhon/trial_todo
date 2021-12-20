<script>
import axios from 'axios'

export default {
  name: 'IndexPage',
  data: () => ({
    tab: null,
    items: [
      { tab: 'pending', content: '' },
      { tab: 'completed', content: '' }
    ],
    valid: false,
    inputTodo: '',
    inputTodoRules: [v => !!v || 'This field is required'],
    todos: {},
    completed: {}
  }),
  async created () {
    await this.getTodos()
    await this.getCompleted()
  },
  methods: {
    async getTodos () {
      try {
        const todos = await axios.get('/todos', { params: { done: 0 } })
        this.todos = todos.data
      } catch (err) {
        console.log(err)
      }
    },
    async getCompleted () {
      try {
        const completed = await axios.get('/todos', { params: { done: 1 } })
        this.completed = completed.data
      } catch (err) {
        console.log(err)
      }
    },
    async addTodo (e) {
      try {
        if (this.$refs.form.validate()) {
          await axios.post('/todos', { text: this.inputTodo })
          // this.todos.splice((this.todos.length - 1), 0, todo.data)
          this.getTodos()
          this.inputTodo = ''
        }
      } catch (err) {
        console.log(err)
      }
    },
    async toggle (todoId, hasDone) {
      try {
        await axios.patch(`/todos/${todoId}`, { done: hasDone })
        await this.getTodos()
        await this.getCompleted()
      } catch (err) {
        console.log(err)
      }
    }
  }
}
</script>

<template>
  <v-row justify="center" align="center">
    <v-col cols="12" sm="12" md="4">
      <v-card>
        <v-card-title class="headline">Checklist</v-card-title>

        <v-card-text>
          <v-tabs v-model="tab">
            <v-tab v-for="item in items" :key="item.tab" @click="item.tab == 'pending'?getTodos():getCompleted()">
              {{ item.tab }}
            </v-tab>
          </v-tabs>

          <v-tabs-items v-model="tab">
            <v-tab-item v-for="item in items" :key="item.tab">
              <v-card flat>
                <v-card-text>
                  <v-list-item-group v-if="item.tab == 'pending'">
                    <v-list-item
                      v-for="todo in todos"
                      :key="todo.text"
                      @click="toggle(todo.id, true)"
                    >
                      <template #default="{ active }">
                        <v-list-item-action>
                          <v-checkbox :input-value="active"></v-checkbox>
                        </v-list-item-action>
                        <v-list-item-content>
                          <v-list-item-subtitle>{{ todo.text }}</v-list-item-subtitle>
                        </v-list-item-content>
                      </template>
                    </v-list-item>
                  </v-list-item-group>

                  <v-list-item-group v-else>
                    <v-list-item
                      v-for="todo in completed"
                      :key="todo.text"
                      @click="toggle(todo.id, false)"
                    >
                      <template #default="{ active }">
                        <v-list-item-action>
                          <v-checkbox :input-value="active"></v-checkbox>
                        </v-list-item-action>
                        <v-list-item-content>
                          <v-list-item-subtitle>{{ todo.text }}</v-list-item-subtitle>
                        </v-list-item-content>
                      </template>
                    </v-list-item>
                  </v-list-item-group>
                </v-card-text>
              </v-card>
            </v-tab-item>
          </v-tabs-items>

            <v-form ref="form" v-model="valid" @submit.prevent="addTodo">
              <v-text-field
                v-model="inputTodo"
                :rules="inputTodoRules"
                label="Add an item here"
                required
              ></v-text-field>
              <v-btn
                :disabled="!valid"
                color="primary"
                class="mr-4"
                @click="addTodo"
              >
                Add item
              </v-btn>
            </v-form>
          </div>
        </v-card-text>
      </v-card>
    </v-col>
  </v-row>
</template>
