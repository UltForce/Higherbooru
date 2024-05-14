// src/components/Navbar.js
import React from "react";
import { Link, useLocation } from "react-router-dom";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faHome, faImage } from "@fortawesome/free-solid-svg-icons";
// Import the external CSS file for Navbar styles
import "./Navbar.css";

const Navbar = () => {
  const location = useLocation();
  return (
    <nav className="navbar-container">
      <div className="logo">
        <img src="/logo.png" alt="logo" />
      </div>
      <ul className="nav-links">
        <li className={location.pathname === "/" ? "active" : ""}>
          <Link to="/">
            {" "}
            <FontAwesomeIcon icon={faHome} /> Home
          </Link>
        </li>
        <li className={location.pathname === "/gallery" ? "active" : ""}>
          <Link to="/gallery">
            {" "}
            <FontAwesomeIcon icon={faImage} />
            Gallery
          </Link>
        </li>
        <li className={location.pathname === "/unsplash" ? "active" : ""}>
          <Link to="/unsplash">View Unsplash Gallery</Link>
        </li>
      </ul>
    </nav>
  );
};

export default Navbar;
