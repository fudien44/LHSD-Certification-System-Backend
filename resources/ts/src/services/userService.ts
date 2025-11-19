import axios from 'axios'
import { globals } from '../globals'
const API_URL = `${globals.api}/api/auth/users`

export const fetchUsers = async () => {
  try {
    const response = await axios.get(API_URL, {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('authToken')}`,
      },
    })

    return response.data
  }
  catch (error) {
    console.error('Error fetching users:', error)
    throw error
  }
}

// CREATE user
export const createUser = async (payload: { name: string; email: string; password: string }) => {
  try {
    const response = await axios.post(API_URL, payload, {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('authToken')}`,
      },
    })
    return response.data
  } catch (error) {
    console.error('Error creating user:', error)
    throw error
  }
}

// UPDATE user
export const updateUser = async (id: number, payload: { name: string; email: string; password?: string }) => {
  try {
    const response = await axios.put(`${API_URL}/${id}`, payload, {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('authToken')}`,
      },
    })
    return response.data
  } catch (error) {
    console.error(`Error updating user ${id}:`, error)
    throw error
  }
}

// DELETE user
export const deleteUser = async (id: number) => {
  try {
    const response = await axios.delete(`${API_URL}/${id}`, {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('authToken')}`,
      },
    })
    return response.data
  } catch (error) {
    console.error(`Error deleting user ${id}:`, error)
    throw error
  }
}
