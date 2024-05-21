import React, { useState } from "react";
import { Link, useLocation } from "react-router-dom";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faHome, faImage } from "@fortawesome/free-solid-svg-icons";
// Import the external CSS file for Navbar styles
import "./Styles/Navbar.css";

const Navbar = () => {
  const location = useLocation();
  const [isOpen, setIsOpen] = useState(false);

  const toggleMenu = () => {
    setIsOpen(!isOpen);
  };

  return (
    <nav className="navbar-container">
      <div className="logo">
        <img src="/logo.png" alt="logo" />
      </div>
      <div className="hamburger" onClick={toggleMenu}>
        <div />
        <div />
        <div />
      </div>
      <ul className={`nav-links ${isOpen ? "open" : ""}`}>
        <li className={location.pathname === "/" ? "active" : ""}>
          <Link to="/" onClick={() => setIsOpen(false)}>
            <FontAwesomeIcon icon={faHome} /> Home
          </Link>
        </li>
        <li className={location.pathname === "/gallery" ? "active" : ""}>
          <Link to="/gallery" onClick={() => setIsOpen(false)}>
            <FontAwesomeIcon icon={faImage} /> Gallery
          </Link>
        </li>
        <li className={location.pathname === "/unsplash" ? "active" : ""}>
          <Link to="/unsplash" onClick={() => setIsOpen(false)}>
            View Unsplash Gallery
          </Link>
        </li>
      </ul>
    </nav>
  );
};

export default Navbar;
