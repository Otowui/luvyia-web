// src/contexts/AuthContext.js
import React, { createContext, useState, useEffect } from 'react';
import { doLogin, getCurrentUser } from '../services/auth';

export const AuthContext = createContext();

export function AuthProvider({ children }) {
	const [user, setUser]       = useState(null);
	const [loading, setLoading] = useState(true);
	
	useEffect(() => {
		(async () => {
		const token = localStorage.getItem('luviya_jwt');
		if (token) {
			try {
				const profile = await getCurrentUser();
				setUser(profile);
			} catch {
				localStorage.removeItem('luviya_jwt');
				setUser(null);
			}
		}
		setLoading(false);
		})();
	}, []);
	
	/**
	   * login() :
	   * - récupère le JWT
	   * - stocke en localStorage
	   * - recharge le profil
	   * - empêche la connexion si email non vérifié
	   * @returns {Promise<Object>} profile utilisateur
	   */
	const login = async (username, password) => {
		// 1) Récupération du token
		const token = await doLogin(username, password);
		localStorage.setItem('luviya_jwt', token);
		
		// 2) Chargement du profil
		const profile = await getCurrentUser();
		
		// 3) Vérification email
		if (!profile.email_verified) {
			// on dégage tout
			localStorage.removeItem('luviya_jwt');
			throw new Error('email_not_verified');
		}
		
		// 4) tout est ok, on connecte
		setUser(profile);
		return profile;
	};
	
	const logout = () => {
		localStorage.removeItem('luviya_jwt');
		setUser(null);
	};
	
	return (
		<AuthContext.Provider value={{ user, loading, login, logout }}>
		{children}
		</AuthContext.Provider>
	);
}