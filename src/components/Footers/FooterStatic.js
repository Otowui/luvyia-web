// src/components/Footer.js
import React from 'react';
import './Footer.css';

export default function Footer() {
  return (
	<footer className="footer">
	  <p>© {new Date().getFullYear()} Luviya. Tous droits réservés.</p>
	</footer>
  );
}