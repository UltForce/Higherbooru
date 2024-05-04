import logo from "./logo.svg";
import "./App.css";
import React from "react";
import { BrowserRouter as Router, Route, Routes } from "react-router-dom";
import Navbar from "./Navigation";
import Introduction from "./Introduction";
import PhotoGallery from "./PhotoGallery";
import Footer from "./Footer";
import UnsplashGallery from "./UnsplashGallery";
function App() {
  return (
    <Router>
      <div>
        <Navbar />
        <Routes>
          <Route path="/" element={<Introduction />} />
          <Route path="/gallery" element={<PhotoGallery />} />
          <Route path="/unsplash" element={<UnsplashGallery />} />
        </Routes>
      </div>
      <Footer />
    </Router>
  );
}

export default App;
