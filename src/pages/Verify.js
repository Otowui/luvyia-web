// src/pages/Verify.js
import React, { useEffect, useState } from 'react';
import { useSearchParams, useNavigate } from 'react-router-dom';
import { verifyEmail } from '../services/auth';

export default function Verify() {
  const [search] = useSearchParams();
  const [status, setStatus] = useState('loading'); // 'loading' | 'success' | 'error'
  const [error, setError]   = useState('');
  const navigate             = useNavigate();
  const token                = search.get('token');

  useEffect(() => {
	if (!token) {
	  setStatus('error');
	  setError('Aucun token fourni');
	  return;
	}
	verifyEmail(token)
	  .then(() => setStatus('success'))
	  .catch(err => {
		setError(err.response?.data?.message || err.message);
		setStatus('error');
	  });
  }, [token]);

  if (status === 'loading') {
	return <p>Vérification en cours…</p>;
  }
  if (status === 'error') {
	return (
	  <div>
		<h2>Échec de la vérification</h2>
		<p style={{ color: 'red' }}>{error}</p>
	  </div>
	);
  }
  // success
  return (
	<div>
	  <h2>Adresse e-mail confirmée !</h2>
	  <p>Votre compte est maintenant activé.</p>
	  <button onClick={() => navigate('/login')}>Se connecter</button>
	</div>
  );
}