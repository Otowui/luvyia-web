// src/pages/Register.js
import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { doRegister } from '../services/auth';

export default function Register() {
  const [form, setForm] = useState({
	username: '',
	email: '',
	password: '',
	birth_date: '',
	accept_cgu: false,
  });
  const [error, setError] = useState(null);
  const [loading, setLoading] = useState(false);
  const navigate = useNavigate();

  const handleChange = e => {
	const { name, value, type, checked } = e.target;
	setForm(prev => ({
	  ...prev,
	  [name]: type === 'checkbox' ? checked : value,
	}));
  };

  const handleSubmit = async e => {
	e.preventDefault();
	setError(null);

	// validation basique
	if (!form.username || !form.email || !form.password || !form.birth_date) {
	  setError('Tous les champs sont requis');
	  return;
	}
	if (!form.accept_cgu) {
	  setError('Vous devez accepter les CGU');
	  return;
	}

	setLoading(true);
	try {
	  const result = await doRegister(
		form.username,
		form.email,
		form.password,
		form.birth_date,
		form.accept_cgu
	  );
	  console.log('Utilisateur créé :', result.user);
	  // rediriger vers la page d'attente
	  ///navigate('/login');
	  navigate('/verify-sent');
	} catch (err) {
	  console.error(err);
	  setError(err.response?.data?.message || err.message);
	} finally {
	  setLoading(false);
	}
  };

  return (
	<div className="register">
	  {error && <p style={{ color: 'red' }}>{error}</p>}
	  <form onSubmit={handleSubmit}>
		<div className="register-form">
			
			<input
				name="username"
				type="text"
				placeholder="Nom d’utilisateur" 
				value={form.username}
				onChange={handleChange}
				required
			/>
			
			<input
				name="email"
				type="email"
				placeholder="Adresse e-mail" 
				value={form.email}
				onChange={handleChange}
				required
			/>
	
			<input
				name="password"
				type="password"
				placeholder="Mot de passe" 
				value={form.password}
				onChange={handleChange}
				required
			/>
	
			
				<label>Date de naissance</label>
				<input
					name="birth_date"
					type="date"
					value={form.birth_date}
					onChange={handleChange}
					required
				/>
			
	
			<label style={{ display: 'flex', alignItems: 'center' }}>
			<input
				name="accept_cgu"
				type="checkbox"
				checked={form.accept_cgu}
				onChange={handleChange}
			/>
			<span>
				J’accepte les <a href="/terms">CGU</a>
			</span>
			</label>
	
			<button type="submit" disabled={loading}>
			{loading ? 'Création…' : 'S’inscrire'}
			</button>
		</div>
	  </form>
	</div>
  );
}