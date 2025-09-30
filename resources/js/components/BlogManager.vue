<template>
  <v-app>
    <v-container>
      <h1>Blog Management</h1>

      <v-data-table
        :items="posts"
        :headers="headers"
        item-key="id"
        class="elevation-1"
        density="comfortable"
      >
        <template v-slot:headers="{ columns }">
          <tr>
            <th v-for="column in columns" :key="column.value" class="text-center bg-blue-grey-lighten-4">
              {{ column.text }}
            </th>
          </tr>
        </template>

        <template v-slot:item.content="{ item }">
          <div v-html="item.content?.rendered ?? item.content" style="max-width: 500px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"></div>
        </template>

        <template v-slot:item.priority="{ item }">
          <v-text-field
            type="number"
            v-model="item.priority"
            @blur="updatePriority(item)"
            style="max-width: 60px"
            hide-details
            density="compact"
          />
        </template>

        <template v-slot:item.actions="{ item }">
          <v-btn size="small" color="blue" class="mr-2" @click="editPost(item)" variant="flat" style="border-radius: 4px; min-width: 36px; min-height: 36px;">
            <i class="fa fa-edit"></i>
          </v-btn>
          <v-btn size="small" color="red" @click="deletePost(item.id)" variant="flat" style="border-radius: 4px; min-width: 36px; min-height: 36px;">
            <i class="fa fa-trash"></i>
          </v-btn>
        </template>
      </v-data-table>

      <v-dialog v-model="editDialog" max-width="600px">
        <v-card>
          <v-card-title>Edit Post</v-card-title>
          <v-card-text>
            <v-text-field
              v-model="editedPost.title"
              label="Title"
            />
            <v-textarea
              v-model="editedPost.content"
              label="Content"
              rows="5"
            />
            <v-textarea
              v-model="editedPost.status"
              label="Status"
            />
            <v-text-field
              v-model.number="editedPost.priority"
              label="Priority"
              type="number"
            />
          </v-card-text>
          <v-card-actions class="d-flex justify-end">
            <v-btn color="primary" @click="saveEdit" class="mr-2">Save</v-btn>
            <v-btn @click="editDialog = false">Cancel</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-container>
  </v-app>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const posts = ref([])

const headers = [
  { text: 'ID', value: 'id' },
  { text: 'Title', value: 'title' },
  { text: 'Content', value: 'content' },
  { text: 'Status', value: 'status' },
  { text: 'Priority', value: 'priority' },
  { text: 'Actions', value: 'actions', sortable: false }
]

const fetchPosts = async () => {
  try {
    const { data } = await axios.get('/api/posts')


    posts.value = data.map(post => ({
      id: post.id,
      title: post.title?.rendered ?? post.title,
      content: post.content?.rendered ?? post.content,
      status: post.status ?? '',
      priority: post.priority ?? 0,
    }))
  } catch (error) {
    console.error('Failed to fetch posts:', error)
  }
}
const editedPost = ref({
  id: null,
  title: '',
  content: '',
  status: '',
  priority: 0,
})

const editDialog = ref(false)

const editPost = (item) => {
  editedPost.value = {
    id: item.id,
    title: item.title?.rendered ?? item.title,
    content: item.content?.rendered ?? item.content,
    status: item.status ?? '',
    priority: item.priority,
  }
  editDialog.value = true
}

const saveEdit = async () => {
  try {
    await axios.put(`/api/posts/${editedPost.value.id}`, editedPost.value)

    const index = posts.value.findIndex(post => post.id === editedPost.value.id)
    if (index !== -1) {
      posts.value[index] = { ...editedPost.value }
    }
    editDialog.value = false
  } catch (error) {
    console.error('Failed to save post:', error)
  }
}

const updatePriority = async (item) => {
  try {
    await axios.post(`/api/posts/${item.id}/priority`, {
      priority: item.priority
    })
  } catch (error) {
    console.error('Failed to update priority:', error)
  }
}

const deletePost = async (id) => {
  try {
    await axios.delete(`/api/posts/${id}`)
    posts.value = posts.value.filter(post => post.id !== id)
  } catch (error) {
    console.error('Failed to delete post:', error)
  }
}

onMounted(fetchPosts)
</script>
