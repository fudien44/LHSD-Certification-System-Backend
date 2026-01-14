import axios from 'axios'
const API_BASE = 'http://10.10.122.29:8000/api'

export const fetchPrograms = async () => {
  const res = await axios.get(`${API_BASE}/programs`)
  return res.data
}

export const createProgram = async (payload: { name: string }) => {
  const res = await axios.post(`${API_BASE}/programs`, payload)
  return res.data
}

export const updateProgram = async (id: number, payload: { name: string }) => {
  const res = await axios.put(`${API_BASE}/programs/${id}`, payload)
  return res.data
}

export const deleteProgram = async (id: number) => {
  await axios.delete(`${API_BASE}/programs/${id}`)
}
