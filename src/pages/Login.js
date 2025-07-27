// src/pages/Login.js
import React, { useState, useContext } from 'react';
import { useNavigate, Link } from 'react-router-dom';
import { AuthContext }       from '../contexts/AuthContext';

export default function Login() {
	const [creds, setCreds] = useState({ username: '', password: '' });
	const [error, setError] = useState(null);
	const { login }         = useContext(AuthContext);
	const navigate          = useNavigate();
	
	const handleSubmit = async e => {
		e.preventDefault();
		try {
			const profile = await login(creds.username, creds.password);
			if (!profile.form_filled) {
				// s’ils n’ont pas rempli le formulaire → onboarding
				return navigate('/onboarding', { replace: true });
			} else {
				// sinon → compte
				navigate('/account', { replace: true });
			}
		} catch(err) {
			if (err.message === 'email_not_verified') {
				// redirige vers la page "vérification envoyée"
				return navigate('/verify-sent', { replace: true });
			}
			setError('Identifiants invalides');
		}
	};
	
	return (
		<form onSubmit={handleSubmit}>
		{error && <p className="error">{error}</p>}
		<input
			type="text"
			placeholder="Nom d’utilisateur"
			value={creds.username}
			onChange={e => setCreds({ ...creds, username: e.target.value })}
		/>
		<input
			type="password"
			placeholder="Mot de passe"
			value={creds.password}
			onChange={e => setCreds({ ...creds, password: e.target.value })}
		/>
		<button type="submit">Connexion</button>
		<p>
			Pas encore de compte ? <Link to="/register">Inscrivez-vous</Link>
		</p>
		</form>
	);
}