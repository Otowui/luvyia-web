// src/pages/NotFound.js
import React from 'react';
import { Link } from 'react-router-dom';

export default function NotFound() {
  return (
	<div style={{
	  textAlign: 'center',
	  padding: '4rem',
	  maxWidth: 600,
	  margin: '0 auto'
	}}>
	  <h1>404 – Page non trouvée</h1>
	  <p>Oups ! La page que vous recherchez n'existe pas.</p>
	  <Link to="/" style={{ color: '#0073aa' }}>
		← Retour à l’accueil
	  </Link>
	</div>
  );
}