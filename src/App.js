// src/App.js
import React from 'react';
import { Routes, Route } from 'react-router-dom';

import Home           from './pages/Home';
import Login          from './pages/Login';
import Register       from './pages/Register';
import Account        from './pages/Account';
import Terms          from './pages/Terms';
import Verify         from './pages/Verify';
import VerifySent     from './pages/VerifySent';
import Onboarding     from './pages/Onboarding';
import NotFound       from './pages/NotFound';
import ProtectedRoute from './components/ProtectedRoute/ProtectedRoute';
import BodyClassController from './components/BodyClassController';

function App() {
  return (
    <>
      <BodyClassController />
      <main className="content">
        <Routes>
          {/* Page d'accueil */}
          
          <Route path="/" element={<Home />} />

          {/* Authentification */}
          <Route path="/login"    element={<Login />} />
          <Route path="/register" element={<Register />} />

          {/* Compte protégé */}
          <Route path="/account" element={<ProtectedRoute><Account /></ProtectedRoute>} />
          <Route path="/onboarding" element={<ProtectedRoute><Onboarding /></ProtectedRoute>} />

          {/* Routes */}
          <Route path="/terms" element={<Terms />} />
          <Route path="/verify" element={<Verify />} />
          <Route path="/verify-sent" element={<VerifySent />} />

          {/* Si l’URL ne matche rien d’autre, on redirige vers l’accueil */}
          <Route path="*" element={<NotFound />} />
        </Routes>
      </main>

    </>
  );
}

export default App;