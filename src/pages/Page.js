// src/pages/Page.js
import React from 'react';
import { useLocation } from 'react-router-dom';
import { useQuery } from '@apollo/client';
import { gql } from '@apollo/client';

const GET_PAGE_BY_URI = gql`
  query($uri: String!) {
	pageBy(uri: $uri) {
	  title
	  content
	}
  }
`;

export default function Page({ uri: forcedUri }) {
  const { pathname } = useLocation();
  // si on fournit forcedUri, on l'utilise, sinon on tombe back sur le pathname
  const uri = forcedUri ?? pathname;
  const { data, loading, error } = useQuery(GET_PAGE_BY_URI, {
    variables: { uri },
  });

  console.log('[Page] Chargement de l’URI', pathname);
  if (loading) return <p>Chargement…</p>;
  if (error)   return <p>Erreur : {error.message}</p>;

  const page = data.pageBy;
  return (
    <article>
      <h1>{page.title}</h1>
      <div dangerouslySetInnerHTML={{ __html: page.content }} />
    </article>
  );
}