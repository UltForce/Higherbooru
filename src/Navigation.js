// src/components/Navbar.js
import React from "react";
import { Link } from "react-router-dom";
// Import the external CSS file for Navbar styles
import "./Navbar.css";

const Navbar = () => {
  return (
    <nav className="navbar-container">
      <div className="logo">
        <img src="/logo.png" alt="logo" />
      </div>
      <ul className="nav-links">
        <li>
          <Link to="/">Home</Link>
        </li>
        <li>
          <Link to="/gallery">Gallery</Link>
        </li>
        <li>
          <Link to="/unsplash">View Unsplash Gallery</Link>
        </li>
      </ul>
    </nav>
  );
};

export default Navbar;
