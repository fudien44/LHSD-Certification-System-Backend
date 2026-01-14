<script lang="ts">
import { createProgram, deleteProgram, fetchPrograms, updateProgram } from '@/src/services/programService';
import { computed, onMounted, ref } from 'vue';

export default {
  setup() {
    const programs = ref<any[]>([]) // start empty, fetch from API
    const search = ref('')
    const dialog = ref(false)
    const isEdit = ref(false)
    const editedProgram = ref<{ id: number | null; name: string }>({ id: null, name: '' })
    const errors = ref<{ name?: string }>({})
    const deleteDialog = ref(false)
    const programToDelete = ref<any | null>(null)
    const deleteError = ref<string>('')

    const headers = [
      { title: 'Program Name', key: 'name' },
      { title: 'Actions', key: 'actions', sortable: false },
    ]

    const data = computed(() =>
       programs.value
    .filter(p => p.name.toLowerCase().includes(search.value.toLowerCase()))
    .map(p => ({
      ...p,
      created_at_formatted: new Date(p.created_at).toLocaleDateString('en-US', {
        month: 'short',
        day: '2-digit',
        year: 'numeric',
      }) + '.',
    }))
    )

    const resetForm = () => {
      editedProgram.value = { id: null, name: '' }
      errors.value = {}
    }

    const loadPrograms = async () => {
      try {
        programs.value = await fetchPrograms()
      } catch (err) {
        console.error('Failed to load programs', err)
      }
    }

    const openCreate = () => {
      isEdit.value = false
      resetForm()
      dialog.value = true
    }

    const openEdit = (item: any) => {
      isEdit.value = true
      editedProgram.value = { ...item }
      errors.value = {}
      dialog.value = true
    }
    const saveSuccess = ref('')
    const saveError = ref('')

    const saveProgram = async () => {
      errors.value = {}
      if (!editedProgram.value.name.trim()) {
        errors.value.name = 'Program name is required'
        return
      }

      try {
       if (isEdit.value && editedProgram.value.id !== null) {
    const updated = await updateProgram(editedProgram.value.id, { name: editedProgram.value.name })
    const index = programs.value.findIndex(p => p.id === updated.id)
    if (index !== -1) programs.value[index] = updated
    saveSuccess.value = `${updated.name} has been updated successfully.`
  } else {
    const created = await createProgram({ name: editedProgram.value.name })
    programs.value.push(created)
    saveSuccess.value = `${created.name} has been added successfully.`
  }
  dialog.value = false
  resetForm()
} catch (err: any) {
  console.error(err)
  saveError.value = 'Failed to save program.'
      }
    }

    const askDeleteProgram = (item: any) => {
      programToDelete.value = item
      deleteError.value = ''
      deleteDialog.value = true
    }

    const confirmDelete = async () => {
      if (!programToDelete.value) return
      try {
        await deleteProgram(programToDelete.value.id)
        programs.value = programs.value.filter(p => p.id !== programToDelete.value.id)
        deleteDialog.value = false
        programToDelete.value = null
      } catch (err: any) {
        console.error('Failed to delete program', err)
        deleteError.value = err?.response?.data?.message || 'Failed to delete program'
      }
    }

    const cancelDelete = () => {
      deleteDialog.value = false
      programToDelete.value = null
      deleteError.value = ''
    }

    onMounted(() => {
      loadPrograms() // 🔹 load data from backend
    })

    return {
      programs,
      headers,
      search,
      data,
      dialog,
      dialogTitle: computed(() => (isEdit.value ? 'Edit Program' : 'Add Program')),
      editedProgram,
      isEdit,
      errors,
      openCreate,
      openEdit,
      saveProgram,
      deleteDialog,
      programToDelete,
      deleteError,
      askDeleteProgram,
      confirmDelete,
      cancelDelete,
    }
  },
}
</script>
<template>
  <!-- Programs Table -->
  <VDataTable :headers="headers" :items="data" item-key="id">
    <!-- Top section: Add button + Search -->
    <template #top>
      <VCardText>
        <VRow>
          <VCol cols="12" md="4">
            <VBtn color="primary" @click="openCreate">
              Add Program
            </VBtn>
          </VCol>
          <VCol cols="12" md="4" offset-md="4">
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

    <!-- Actions column -->
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
        @click="askDeleteProgram(item)"
      />
    </template>
  </VDataTable>

  <!-- Add/Edit Program Dialog -->
  <VDialog v-model="dialog" max-width="500">
    <VCard>
      <VCardTitle>{{ dialogTitle }}</VCardTitle>
      <VCardText>
        <VForm @submit.prevent="saveProgram">
          <VRow>
            <VCol cols="12">
              <AppTextField
                v-model="editedProgram.name"
                label="Program Name"
                :error="!!errors.name"
                :error-messages="errors.name"
                required
              />
            </VCol>
          </VRow>

          <VCardActions class="justify-end mt-4">
            <VBtn variant="text" @click="dialog = false">Cancel</VBtn>
            <VBtn type="submit">Save</VBtn>
          </VCardActions>
        </VForm>
      </VCardText>
    </VCard>
  </VDialog>

  <!-- Delete Confirmation Dialog -->
  <VDialog v-model="deleteDialog" max-width="400">
    <VCard>
      <VCardTitle class="text-h6">Confirm Deletion</VCardTitle>
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
          <strong>{{ programToDelete?.name }}</strong>?
        </p>
        <p class="text-caption text-medium-emphasis mb-0">
          This action cannot be undone.
        </p>
      </VCardText>
      <VCardActions class="justify-end">
        <VBtn variant="text" @click="cancelDelete">Cancel</VBtn>
        <VBtn color="error" @click="confirmDelete">Delete</VBtn>
      </VCardActions>
    </VCard>
  </VDialog>
</template>
