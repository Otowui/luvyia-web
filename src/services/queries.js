// src/services/queries.js
import { gql } from '@apollo/client';

export const GET_HOME_PAGE = gql`
  query GetHomePage {
	pages(where: { name: "my-account" }) {
	  nodes {
		title
		content
	  }
	}
  }
`;