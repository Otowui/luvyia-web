// src/pages/Home.js
import React, { useContext } from 'react';
import { Navigate, Link } from 'react-router-dom';
import { AuthContext } from '../contexts/AuthContext';
import './Home.css';

export default function Home() {
  const { user, loading } = useContext(AuthContext);

  if (loading) return <div className="loader">Chargement…</div>;
  if (user)    return <Navigate to="/account" replace />;

  return (
	<div className="home">
		<h1>logo</h1>
		<div className="actions">
			<Link to="/login"><button>Se connecter</button></Link>
			<Link to="/register"><button>S'inscrire</button></Link>
			<div className="disclaimer">
				<p>En cliquant sur s'inscrire, vous accepter nos <a href="/terms">conditions générales d'utilisation</a> et notre <a href="/terms">Politique de sécurité relative aux rencontres en ligne</a>. Découvrez comment nous collectons, utilisons et partageons vos données dans notre <a href="/terms">Protection des données</a></p>
			</div>
			<div className="copyright">
				<p>© {new Date().getFullYear()} Luviya. Tous droits réservés.</p>
			</div>
		</div>
	</div>
  );
}