<script lang="ts">
import { createUser, deleteUser, fetchUsers, updateUser } from '@/src/services/userService';
import { computed, onMounted, ref } from 'vue';

export default {
  setup() {
    const users = ref<any[]>([])
    const search = ref('')
     const dialog = ref(false)
    const isEdit = ref(false)
    const loading = ref(false)
     const editedUser = ref<{ id: number | null; name: string; email: string; password: string }>({
      id: null,
      name: '',
      email: '',
      password: '',
    })
     const errors = ref<{ name?: string; email?: string; password?: string }>({})
      // 🔔 NEW: delete confirmation dialog state
    const deleteDialog = ref(false)
    const userToDelete = ref<any | null>(null)
    const deleteError = ref<string>('')
    const dialogTitle = computed(() => (isEdit.value ? 'Edit User' : 'Add User'))
  

    const headers = [
  { title: 'Name', key: 'full_name' },
  { title: 'Email', key: 'email' },
  { title: 'Date Created', key: 'created_at_formatted' },
  { title: 'Actions', key: 'actions', sortable: false },
    ]
  const formatDate = (dateString: string) => {
  if (!dateString) return ''
  const date = new Date(dateString)

  return date.toLocaleDateString('en-US', {
    month: 'short',   // "Feb"
    day: '2-digit',   // "01"
    year: 'numeric',  // "2025"
  }) + '.'
}
    

   const data = computed(() =>
  users.value.map(user => ({
    ...user,
    full_name: user.name,
    created_at_formatted: formatDate(user.created_at),
  }))
) 
const resetForm = () => {
      editedUser.value = {
        id: null,
        name: '',
        email: '',
        password: '',
      }
      errors.value = {}
    }

    const openCreate = () => {
      isEdit.value = false
      resetForm()
      dialog.value = true
    }

     const getRow = (item: any) => {
      if (item?.raw) return item.raw      // Vuetify 3
      if (item?.item) return item.item    // Vuetify 2
      return item ?? null                 // plain row
    }

   const openEdit = (item: any) => {
  const row = getRow(item)
  if (!row)
    return

  isEdit.value = true
  errors.value = {}

  editedUser.value = {
    id: row.id,
    name: row.name ?? row.full_name,
    email: row.email,
    password: '',
  }
  dialog.value = true
}
  const isValidEmail = (value: string) => {
      const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
      return re.test(value)
    }

    const saveUser = async () => {
      errors.value = {}

      
      if (!editedUser.value.name.trim())
        errors.value.name = 'Name is required'

      if (!editedUser.value.email.trim())
        errors.value.email = 'Email is required'
      else if (!isValidEmail(editedUser.value.email))
        errors.value.email = 'Please enter a valid email address'

          // Password rules
      if (!isEdit.value) {
        // Create: required AND min 8 characters
        if (!editedUser.value.password.trim())
          errors.value.password = 'Password is required'
        else if (editedUser.value.password.length < 8)
          errors.value.password = 'Password must be at least 8 characters'
      } else {
        // Edit: optional, but if filled, min 8 characters
        if (editedUser.value.password && editedUser.value.password.length < 8)
          errors.value.password = 'Password must be at least 8 characters'
      }

      if (Object.keys(errors.value).length > 0)
        return

      loading.value = true

      try {
        if (isEdit.value && editedUser.value.id) {
          await updateUser(editedUser.value.id, {
            name: editedUser.value.name,
            email: editedUser.value.email,
            // send password only if filled (backend treats null/empty as "keep existing")
            ...(editedUser.value.password ? { password: editedUser.value.password } : {}),
          })
        }
        else {
          await createUser({
            name: editedUser.value.name,
            email: editedUser.value.email,
            password: editedUser.value.password,
          })
        }

        dialog.value = false
        resetForm()
        await loadUsers()
      }
      catch (error) {
        console.error('Failed to save user', error)
      }
      finally {
        loading.value = false
      }
    }

         // 🔔 NEW: open confirmation dialog
    const askDeleteUser = (item: any) => {
      const row = getRow(item)
      if (!row) return

      userToDelete.value = row
      deleteError.value = ''
      deleteDialog.value = true
    }

    // 🔔 NEW: confirm deletion
    const confirmDelete = async () => {
      if (!userToDelete.value)
        return

      try {
        deleteError.value = ''
        await deleteUser(userToDelete.value.id)
        deleteDialog.value = false
        userToDelete.value = null
        await loadUsers()
      } catch (error: any) {
        console.error('Failed to delete user', error)

        // handle backend message like "Cannot delete currently logged in user"
        const message = error?.response?.data?.message || 'Failed to delete user.'
        deleteError.value = message
      }
    }

    const cancelDelete = () => {
      deleteDialog.value = false
      userToDelete.value = null
      deleteError.value = ''
    }

    const loadUsers = async () => {
      try {
        users.value = await fetchUsers()
      } catch (error) {
        console.error('Failed to fetch users', error)
      }
    }

    onMounted(() => {
      loadUsers()
    })

     return {
      users,
      headers,
      search,
      data,
      dialog,
      dialogTitle,
      editedUser,
      isEdit,
      loading,
      openCreate,
      openEdit,
      saveUser,

      // delete dialog
      deleteDialog,
      userToDelete,
      deleteError,
      askDeleteUser,
      confirmDelete,
      cancelDelete,

      errors,
    }
  },
}
</script>

<template>
  <VDataTable
    :headers="headers"
    :items="data"
    :search="search"
    item-key="id"
  >
    <template #top>
      <VCardText>
        <VRow>
          <VCol cols="12" md="4">
            <VBtn color="primary" @click="openCreate">
              Add User
            </VBtn>
          </VCol>

          <VCol
            cols="12"
            offset-md="4"
            md="4"
          >
            <AppTextField
              v-model="search"
              placeholder="Search ..."
              append-inner-icon="tabler-search"
              single-line
              hide-details
              dense
              outlined
            />
          </VCol>
        </VRow>
      </VCardText>
    </template>
    <template #item.actions="{ item }">
      <VBtn
        icon="tabler-edit"
    size="small"
    variant="text"
    @click="openEdit(item)"
      />
      <VBtn
       icon="tabler-trash"
    size="small"
    variant="text"
    color="error"
    @click="askDeleteUser(item)"
      />
    </template>
  </VDataTable>
  <VDialog
  v-model="deleteDialog"
  max-width="400"
>
  <VCard>
    <VCardTitle class="text-h6">
      Confirm Deletion
    </VCardTitle>

    <VCardText>
      <VAlert
        v-if="deleteError"
        type="error"
        variant="tonal"
        class="mb-3"
      >
        {{ deleteError }}
      </VAlert>

      <p>
        Are you sure you want to delete
        <strong>{{ userToDelete?.name }}</strong>?
      </p>
      <p class="text-caption text-medium-emphasis mb-0">
        This action cannot be undone.
      </p>
    </VCardText>

    <VCardActions class="justify-end">
      <VBtn
        variant="text"
        @click="cancelDelete"
      >
        Cancel
      </VBtn>
      <VBtn
        color="error"
        @click="confirmDelete"
      >
        Delete
      </VBtn>
    </VCardActions>
  </VCard>
</VDialog>
  <VDialog
    v-model="dialog"
    max-width="500"
  >
    <VCard>
      <VCardTitle>{{ dialogTitle }}</VCardTitle>

      <VCardText>
        <VForm @submit.prevent="saveUser">
          <VRow>
            <VCol cols="12">
              <AppTextField
                v-model="editedUser.name"
                label="Name"
                :error="!!errors.name"
                :error-messages="errors.name"
                required
              />
            </VCol>

            <VCol cols="12">
              <AppTextField
                v-model="editedUser.email"
                label="Email"
                type="email"
                :error="!!errors.email"
                :error-messages="errors.email"
                required
              />
            </VCol>

            <VCol cols="12">
              <AppTextField
                v-model="editedUser.password"
                label="Password"
                :type="'password'"
                :hint="isEdit ? 'Leave blank to keep current password' : 'Password must be at least 8 characters'"
                persistent-hint
                :error="!!errors.password"
                :error-messages="errors.password"
              />
            </VCol>
          </VRow>

          <VCardActions class="justify-end mt-4">
            <VBtn
              variant="text"
              @click="dialog = false"
            >
              Cancel
            </VBtn>
            <VBtn
              type="submit"
              :loading="loading"
            >
              Save
            </VBtn>
          </VCardActions>
        </VForm>
      </VCardText>
    </VCard>
  </VDialog>
</template>
