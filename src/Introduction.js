// src/components/Introduction.js
import React from "react";
import "./styles.css";
const Introduction = () => {
  return (
    <>
       <div className="introduction">
      <div className="image-container">
            <img src="./CanvaCorner-3ITF.png" alt="Introduction" />
            <div className="text-button-container h2">
              <h2> Your Art Journey</h2>
              <h2> Begins Here</h2>
              <div className="text-button-container">
              <button className="explore-button">Explore Now</button>
              </div>
    </div>
        </div>
       
        
      </div>
      <div className="buy-plans">
        <h2>IT IS A PLACE WHERE YOU CAN SHOWCASE YOUR ARTISTIC TALENTS AND EXPOSE YOUR WORK</h2>
        <div className="subscription-options">
          {/* Placeholder content for subscription options */}
          <div className="subscription-option">
            
            <h3>PAINTING & ART</h3>
            <div className="image-content">
              <img src="./paint.jpg" alt="paint"/>
            </div>
            <p>Our unique artwork appraisal system will evaluate your work on daily basis</p>
          </div>
          <div className="subscription-option">
            <h3>PHOTOGRAPHY</h3>
            <div className="image-content">
              <img src="./photo.jpg" alt="paint"/>
            </div>
            <p>Create a visual representation of a moment, scene, or subject</p>
          </div>
          <div className="subscription-option">
            <h3>SCULPTURES</h3>
            <div className="image-content">
              <img src="./sculpture.jpg" alt="paint"/>
            </div>
            <p>Viewed from various angles, often conveying artistic expression</p>
          </div>
        </div>
      </div>

      <div classname="About-us"> 
      <div className="about-container">
        <img src="./Aboutus.png" alt="aboutus" />
        <div className="aboutus-text-container">
          <h2>Find Art You Love</h2>
        </div>
        <div className="aboutus-p-container">
          <p><p>“We make it our mission to help you discover and buy from the best emerging artists around the world. Whether you’re looking to discover a new artist, add a statement piece to your home, or commemorate an important life event, Canva Corner is your portal to thousands of original works by today’s top artists.”</p></p>
        </div>
      
      


      </div>
        
      </div>
      
    </>
  );
};

export default Introduction;
