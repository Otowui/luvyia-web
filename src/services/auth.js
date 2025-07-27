// src/services/auth.js
import axios from 'axios';

// 1) Toujours envoyer le token JWT si présent
axios.defaults.withCredentials = false;
axios.interceptors.request.use(config => {
    const t = localStorage.getItem('luviya_jwt');
    if (t) config.headers.Authorization = `Bearer ${t}`;
    return config;
});

const API = process.env.REACT_APP_API_REST;

// 1) LOGIN : récupère le JWT
export async function doLogin(username, password) {
  const { data } = await axios.post(
    `${API}/jwt-auth/v1/token`,
    { username, password }
  );
  return data.token;
}

/**
 * Enregistre un nouvel utilisateur
 * @param {string} username
 * @param {string} email
 * @param {string} password
 * @param {string} birth_date    // format "YYYY-MM-DD"
 * @param {boolean} accept_cgu   // true si CGU cochées
 */
export async function doRegister(username, email, password, birth_date, accept_cgu) {
  const res = await axios.post(
    `${API}/custom/v1/register`,
    {
      username,
      email,
      password,
      birth_date,
      accept_cgu,
    }
  );
  return res.data; // selon votre callback WP, vous pouvez renvoyer token ou user
}

// 3) (Optionnel) Register, si vous exposez un endpoint register
export async function validateToken(token) {
  const { data } = await axios.post(
    `${API}/jwt-auth/v1/token/validate`,
    { token }
  );
  // data.user contient id, username, email, etc.
  return data.user;
}

/**
 * Récupère le profil complet de l'utilisateur connecté
 * via votre endpoint custom/v1/me.
 */
export async function getCurrentUser() {
  const { data } = await axios.get(`${API}/custom/v1/me`);
  console.debug(data);
  return data;  // { id, username, email, first_name, last_name, roles, avatar_urls, … }
}

/**
 * Récupère le profil complet de l’utilisateur connecté via l'API WP native.
 * Retourne un objet contenant tous les champs : id, name, email, slug, url,
 * description, roles, avatar_urls, et tout autre métachamp exposé.
 */
export async function getUserProfile() {
   const token = localStorage.getItem('luviya_jwt');
   const { data } = await axios.get(
     `${API}/wp/v2/users/me`,
     { headers: { Authorization: `Bearer ${token}` } }
   );
   return data;  // { id, name, first_name, last_name, email, roles, avatar_urls, ... }
 }

/**
  * Vérifie le token envoyé par e-mail
  */
export async function verifyEmail(token) {
  const { data } = await axios.get(
    `${API}/custom/v1/verify`,
    { params: { token } }
  );
  return data;
}