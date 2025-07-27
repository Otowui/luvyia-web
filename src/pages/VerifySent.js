// src/pages/VerifySent.js
import React from 'react';
import { useNavigate } from 'react-router-dom';

export default function VerifySent() {
  const navigate = useNavigate();

  return (
	<div style={{
	  maxWidth: 500,
	  margin: '3rem auto',
	  padding: '2rem',
	  textAlign: 'center',
	  border: '1px solid #ddd',
	  borderRadius: 4,
	  boxShadow: '0 2px 8px rgba(0,0,0,0.1)'
	}}>
	  <h2>🎉 Email de confirmation envoyé !</h2>
	  <p>
		Nous venons de vous envoyer un e-mail pour vérifier votre adresse.  
		Cliquez sur le lien dans ce message pour activer votre compte.
	  </p>
	  <p style={{ fontSize: '0.9rem', color: '#555' }}>
		Pensez à vérifier vos spams si vous ne le trouvez pas dans votre boîte de réception.
	  </p>
	  <button
		onClick={() => navigate('/login')}
		style={{
		  marginTop: '1.5rem',
		  padding: '0.6rem 1.2rem',
		  cursor: 'pointer',
		  border: 'none',
		  borderRadius: 4,
		  backgroundColor: '#0073aa',
		  color: '#fff'
		}}
	  >
		Retour à la connexion
	  </button>
	</div>
  );
}