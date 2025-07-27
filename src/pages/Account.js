// src/pages/Account.js
import React, { useContext } from 'react';
import { useNavigate } from 'react-router-dom';
import { AuthContext } from '../contexts/AuthContext';

export default function Account() {
  const { user, logout } = useContext(AuthContext);
  const navigate = useNavigate();

  // Formatage local FR pour les dates
  const formatDate = isoString => {
	try {
	  return new Date(isoString).toLocaleDateString('fr-FR');
	} catch {
	  return isoString;
	}
  };

  const handleLogout = () => {
	logout();
	navigate('/login', { replace: true });
  };

  if (!user) {
	return <p>Vous n’êtes pas connecté·e.</p>;
  }

  return (
	<div style={{ textAlign: 'center', padding: '2rem' }}>
	  <h1>Mon compte</h1>
	  <img
		src={user.avatar_urls}
		alt="Avatar"
		style={{ borderRadius: '50%', marginBottom: '1rem' }}
	  />

	  <p><strong>Nom d’utilisateur :</strong> {user.username}</p>
	  <p><strong>Affiché :</strong> {user.display_name}</p>
	  <p><strong>Prénom :</strong> {user.first_name}</p>
	  <p><strong>Nom :</strong> {user.last_name}</p>
	  <p><strong>E-mail :</strong> {user.email}</p>
	  <p><strong>Rôles :</strong> {user.roles.join(', ')}</p>

	  <p>
		<strong>Date de naissance :</strong>{' '}
		{user.birth_date ? formatDate(user.birth_date) : '—'}
	  </p>
	  <p>
		<strong>CGU acceptées :</strong>{' '}
		{user.accept_cgu
		  ? `Oui (${user.cgu_accepted_at ? formatDate(user.cgu_accepted_at) : ''})`
		  : 'Non'}
	  </p>

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