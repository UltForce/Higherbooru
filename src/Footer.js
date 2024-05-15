import React from "react";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faFacebook, faInstagram } from "@fortawesome/free-brands-svg-icons";
import "./styles.css";

const Footer = () => {
  return (
    <footer className="footer">
      <div className="footer-content">
        <div className="social-media">
          <a
            href="https://web.facebook.com"
            target="_blank"
            rel="noopener noreferrer"
          >
            <FontAwesomeIcon icon={faFacebook} className="white" />
            <span className="social-media-label white"> Facebook</span>
          </a>
          <br />
          <a
            href="https://www.instagram.com"
            target="_blank"
            rel="noopener noreferrer"
          >
            <FontAwesomeIcon icon={faInstagram} className="white" />
            <span className="social-media-label white"> Instagram</span>
          </a>
        </div>
        <div>
          <p className="white">Contact us: 09777549108</p>
          <p className="white">Email: Higherbooru@gmail.com</p>
        </div>
      </div>

      <p className="white">&copy; 2024 Higherbooru. All rights reserved.</p>
    </footer>
  );
};

export default Footer;