// src/utils/logger.js
// à utiliser ulterieurement => console.log par debug('Auth login', token)
export function debug(...args) {
  if (process.env.NODE_ENV === 'development') {
	console.log('[DEBUG]', ...args);
  }
}