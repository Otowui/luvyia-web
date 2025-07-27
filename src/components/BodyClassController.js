// src/components/BodyClassController.js
import { useEffect } from 'react';
import { useLocation } from 'react-router-dom';

export default function BodyClassController() {
  const { pathname } = useLocation();

  useEffect(() => {
	// transforme "/login" → "page-login", "/" → "page-home"
	const slug = pathname === '/' ? 'home' : pathname.replace(/^\//, '').replace(/\//g, '-');
	const className = `page-${slug}`;

	document.body.classList.add(className);
	// cleanup à la navigation suivante
	return () => {
	  document.body.classList.remove(className);
	};
  }, [pathname]);

  return null;
}