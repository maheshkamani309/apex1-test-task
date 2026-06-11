import { ref, computed } from 'vue'

const user = ref(null)
const token = ref(localStorage.getItem('auth_token'))
const loading = ref(false)

const isAuthenticated = computed(() => !!token.value)

const setToken = (newToken) => {
    token.value = newToken
    if (newToken) {
        localStorage.setItem('auth_token', newToken)
    } else {
        localStorage.removeItem('auth_token')
    }
}

const setUser = (newUser) => {
    user.value = newUser
}

const login = async (email, password) => {
    loading.value = true
    try {
        const response = await fetch('/api/login', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ email, password }),
        })

        const data = await response.json()

        if (data.success) {
            setToken(data.token)
            setUser(data.user)
            return { success: true }
        } else {
            return { success: false, message: data.message || 'Login failed' }
        }
    } catch (err) {
        console.error(err)
        return { success: false, message: 'An error occurred' }
    } finally {
        loading.value = false
    }
}

const logout = async () => {
    loading.value = true
    try {
        const response = await fetch('/api/logout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token.value}`,
            },
        })

        if (response.ok) {
            setToken(null)
            setUser(null)
            window.location.href = '/login'
        } else {
            return { success: false, message: 'Logout failed' }
        }
    } catch (err) {
        console.error(err)
        setToken(null)
        setUser(null)
         window.location.href = '/login'
    } finally {
        loading.value = false
    }
}

const fetchUser = async () => {
    if (!token.value) return
    
    loading.value = true
    try {
        const response = await fetch('/api/user', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token.value}`,
            },
        })

        const data = await response.json()

        if (data.success) {
            setUser(data.user)
        } else {
            setToken(null)
            setUser(null)
        }
    } catch (err) {
        console.error(err)
        setToken(null)
        setUser(null)
    } finally {
        loading.value = false
    }
}

export const useAuth = () => ({
    user,
    token,
    loading,
    isAuthenticated,
    login,
    logout,
    fetchUser,
    setToken,
    setUser,
})
