// src/pages/Onboarding.js
import React, { useContext, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { AuthContext } from '../contexts/AuthContext';

export default function Onboarding() {
	const { user, loading, logout } = useContext(AuthContext);
	const navigate = useNavigate();
	
	useEffect(() => {
		if (!loading) {
		if (!user) {
			// Pas loggué → vers login
			navigate('/login', { replace: true });
		} else if (user.form_filled) {
			// Déjà fait → vers account
			navigate('/account', { replace: true });
		}
		}
	}, [user, loading, navigate]);
	
	const handleLogout = () => {
		logout();
		navigate('/login', { replace: true });
		};
	
	if (loading || !user) {
		// Optionnel : afficher un loader
		return <p>Chargement…</p>;
	}
	
	// Votre form multi-étapes ici
	return (
		<div style={{ padding: '2rem', textAlign: 'center' }}>
		  <h1>Complétez votre profil</h1>
		  <p>Avant d’accéder à votre compte, merci de répondre à quelques questions.</p>
		  
		  {/* Ici vos champs du multistep form… */}
	
		  {/* Bouton Déconnexion */}
		  <button
			onClick={handleLogout}
			style={{
			  marginTop: '2rem',
			  padding: '0.5rem 1rem',
			  cursor: 'pointer',
			  backgroundColor: '#e53e3e',
			  color: '#fff',
			  border: 'none',
			  borderRadius: 4
			}}
		  >
			Se déconnecter
		  </button>
		</div>
	  );
	}