// src/pages/Terms.js
import React, { useState, useEffect } from 'react';
import HeaderStatic                 from '../components/Headers/HeaderStatic';
import FooterStatic                 from '../components/Footers/FooterStatic';
import axios from 'axios';

export default function Terms() {
  const [page, setPage]       = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError]     = useState(null);

  useEffect(() => {
	// Remplacez l'ID "3" par celui de votre page Terms dans WP
	axios
	  .get(`${process.env.REACT_APP_API_REST}/wp/v2/pages/3`)
	  .then(res => setPage(res.data))
	  .catch(err => setError(err))
	  .finally(() => setLoading(false));
  }, []);

  if (loading) return <p>Chargement des CGU…</p>;
  if (error)   return <p>Erreur : {error.message}</p>;
  if (!page)   return null;

  return (
	  <>
		<HeaderStatic />
		<main style={{ padding: '2rem', maxWidth: 800, margin: '0 auto' }}>
		  {loading && <p>Chargement des CGU…</p>}
		  {error   && <p>Erreur : {error.message}</p>}
		  {page    && (
			<>
			  <h1 dangerouslySetInnerHTML={{ __html: page.title.rendered }} />
			  <div dangerouslySetInnerHTML={{ __html: page.content.rendered }} />
			</>
		  )}
		</main>
		<FooterStatic />
	  </>
	);
}