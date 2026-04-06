import { createContext, useContext, useMemo, useState } from 'react';
import api from '../api/client';

const AuthContext = createContext(null);

export function AuthProvider({ children }) {
  const [user, setUser] = useState(null);

  const login = async (payload) => {
    const { data } = await api.post('/login', payload);
    localStorage.setItem('token', data.token);
    setUser(data.user);
  };

  const register = async (payload) => {
    const { data } = await api.post('/register', payload);
    localStorage.setItem('token', data.token);
    setUser(data.user);
  };

  const logout = async () => {
    await api.post('/logout');
    localStorage.removeItem('token');
    setUser(null);
  };

  const value = useMemo(() => ({ user, setUser, login, register, logout }), [user]);
  return <AuthContext.Provider value={value}>{children}</AuthContext.Provider>;
}

export const useAuth = () => useContext(AuthContext);
